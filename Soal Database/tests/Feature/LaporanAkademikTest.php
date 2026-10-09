<?php

namespace Tests\Feature;

use App\Models\MataPelajaran;
use App\Models\Peserta;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanAkademikTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_count_participants_and_require_passing_every_exam(): void
    {
        $matematika = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $bahasa = MataPelajaran::create(['nama_matpel' => 'Bahasa Indonesia']);
        $ujianA = $matematika->ujian()->create(['nama_ujian' => 'UTS Matematika', 'tanggal' => '2026-10-12 08:00:00']);
        $ujianB = $bahasa->ujian()->create(['nama_ujian' => 'UTS Bahasa', 'tanggal' => '2026-10-13 09:00:00']);

        foreach (['001' => 'Andi', '002' => 'Budi', '003' => 'Citra', '004' => 'Deni', '005' => 'Eka'] as $nis => $nama) {
            Siswa::create(['nis' => $nis, 'nama' => $nama, 'alamat' => 'Bandung']);
        }
        foreach (['001' => [75, 90], '002' => [60, 85], '003' => [80, null]] as $nis => $nilai) {
            Peserta::create(['nis' => $nis, 'id_ujian' => $ujianA->id_ujian, 'nilai' => $nilai[0]]);
            Peserta::create(['nis' => $nis, 'id_ujian' => $ujianB->id_ujian, 'nilai' => $nilai[1]]);
        }
        Peserta::create(['nis' => '005', 'id_ujian' => $ujianA->id_ujian, 'nilai' => 100]);

        $this->get(route('laporan.index'))->assertOk()
            ->assertViewHas('jumlahLulusSemua', 1)
            ->assertViewHas('lulusSemua', fn ($rows) => $rows->pluck('nis')->all() === ['001'])
            ->assertViewHas('ujian', fn ($rows) => $rows->pluck('peserta_count', 'id_ujian')->all() === [$ujianA->id_ujian => 4, $ujianB->id_ujian => 3])
            ->assertViewHas('ujian', fn ($rows) => $rows->pluck('peserta_lulus_count', 'id_ujian')->all() === [$ujianA->id_ujian => 3, $ujianB->id_ujian => 2])
            ->assertSee('Jumlah Peserta Lulus')
            ->assertViewHas('gagal', fn ($rows) => $rows->count() === 1 && $rows->first()->nama === 'Budi' && $rows->first()->peserta->first()->ujian->mataPelajaran->nama_matpel === 'Matematika');

        Peserta::where('nis', '002')->where('id_ujian', $ujianA->id_ujian)->update(['nilai' => 75]);
        $this->get(route('laporan.index'))->assertViewHas('jumlahLulusSemua', 2)->assertViewHas('gagal', fn ($rows) => $rows->isEmpty());
    }

    public function test_date_filter_matches_calendar_day_and_returns_zero_when_no_exams_match(): void
    {
        $matpel = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $siswa = Siswa::create(['nis' => '001', 'nama' => 'Andi', 'alamat' => 'Bandung']);
        foreach (['2026-10-12 08:00:00', '2026-10-12 14:00:00', '2026-10-13 08:00:00'] as $tanggal) {
            $ujian = $matpel->ujian()->create(['nama_ujian' => 'UTS', 'tanggal' => $tanggal]);
            Peserta::create(['nis' => $siswa->nis, 'id_ujian' => $ujian->id_ujian, 'nilai' => 80]);
        }
        foreach (['laporan.index', 'ujian.index'] as $route) {
            $this->get(route($route, ['tanggal' => '12/10/2026']))->assertOk()
                ->assertViewHas($route === 'ujian.index' ? 'daftarUjian' : 'ujian', fn ($rows) => $rows->total() === 2);
        }
        $this->get(route('laporan.index', ['tanggal' => '14/10/2026']))->assertOk()
            ->assertViewHas('ujian', fn ($rows) => $rows->isEmpty())
            ->assertViewHas('jumlahLulusSemua', 0);
        $this->get(route('laporan.index', ['tanggal' => '30/02/2026']))->assertSessionHasErrors('tanggal');
    }

    public function test_empty_exam_database_does_not_mark_students_as_passed(): void
    {
        Siswa::create(['nis' => '001', 'nama' => 'Andi', 'alamat' => 'Bandung']);
        $this->get('/')->assertOk()->assertViewHas('jumlahLulusSemua', 0);
    }

    public function test_filtered_kelulusan_requires_passing_all_exams_only_on_selected_date(): void
    {
        $matpel = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $pagi = $matpel->ujian()->create(['nama_ujian' => 'Ujian Pagi', 'tanggal' => '2026-10-12 08:00:00']);
        $sore = $matpel->ujian()->create(['nama_ujian' => 'Ujian Sore', 'tanggal' => '2026-10-12 14:00:00']);
        $besok = $matpel->ujian()->create(['nama_ujian' => 'Ujian Besok', 'tanggal' => '2026-10-13 08:00:00']);

        foreach (['001' => 'Andi', '002' => 'Budi', '003' => 'Citra', '004' => 'Deni'] as $nis => $nama) {
            Siswa::create(['nis' => $nis, 'nama' => $nama, 'alamat' => 'Bandung']);
            Peserta::create(['nis' => $nis, 'id_ujian' => $pagi->id_ujian, 'nilai' => 75]);
        }
        Peserta::create(['nis' => '001', 'id_ujian' => $sore->id_ujian, 'nilai' => 80]);
        Peserta::create(['nis' => '001', 'id_ujian' => $besok->id_ujian, 'nilai' => 50]);
        Peserta::create(['nis' => '003', 'id_ujian' => $sore->id_ujian, 'nilai' => null]);
        Peserta::create(['nis' => '004', 'id_ujian' => $sore->id_ujian, 'nilai' => 74.99]);

        $this->get(route('laporan.index', ['tanggal' => '12/10/2026']))->assertOk()
            ->assertViewHas('jumlahLulusSemua', 1)
            ->assertViewHas('lulusSemua', fn ($rows) => $rows->pluck('nis')->all() === ['001'])
            ->assertSee('Siswa lulus semua ujian pada 12/10/2026');
        $this->get(route('laporan.index'))->assertOk()->assertViewHas('jumlahLulusSemua', 0);
        $this->get(route('laporan.index', ['tanggal' => '13/10/2026']))->assertOk()->assertViewHas('jumlahLulusSemua', 0);
    }

    public function test_exams_without_participants_are_displayed_with_zero_count(): void
    {
        $matpel = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $matpel->ujian()->create(['nama_ujian' => 'UAS', 'tanggal' => '2026-10-12 08:00:00']);
        $this->get(route('laporan.index'))->assertOk()
            ->assertViewHas('jumlahLulusSemua', 0)
            ->assertViewHas('ujian', fn ($rows) => $rows->first()->peserta_count === 0 && $rows->first()->peserta_lulus_count === 0);
    }

    public function test_total_passed_count_is_not_limited_to_one_page(): void
    {
        $matpel = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $ujian = $matpel->ujian()->create(['nama_ujian' => 'UTS', 'tanggal' => '2026-10-12 08:00:00']);
        for ($i = 1; $i <= 16; $i++) {
            $siswa = Siswa::create(['nis' => 'S'.$i, 'nama' => 'Siswa '.$i, 'alamat' => 'Bandung']);
            Peserta::create(['nis' => $siswa->nis, 'id_ujian' => $ujian->id_ujian, 'nilai' => 75]);
        }
        $this->get(route('laporan.index'))->assertOk()->assertViewHas('jumlahLulusSemua', 16)
            ->assertViewHas('lulusSemua', fn ($rows) => $rows->count() === 15 && $rows->total() === 16);
    }

    public function test_failed_students_appear_once_with_all_failed_grades(): void
    {
        $siswa = Siswa::create(['nis' => '001', 'nama' => 'Budi', 'alamat' => 'Bandung']);
        foreach (['Matematika' => 60, 'Bahasa Indonesia' => 65, 'Fisika' => 75, 'Kimia' => null] as $namaMatpel => $nilai) {
            $matpel = MataPelajaran::create(['nama_matpel' => $namaMatpel]);
            $ujian = $matpel->ujian()->create(['nama_ujian' => 'UTS '.$namaMatpel, 'tanggal' => '2026-10-12 08:00:00']);
            Peserta::create(['nis' => $siswa->nis, 'id_ujian' => $ujian->id_ujian, 'nilai' => $nilai]);
        }

        $response = $this->get(route('laporan.index'))->assertOk()
            ->assertViewHas('gagal', fn ($rows) => $rows->total() === 1
                && $rows->first()->nis === '001'
                && $rows->first()->peserta->pluck('nilai')->all() === ['60.00', '65.00'])
            ->assertSee('60.00')->assertSee('65.00');
        $this->assertSame(1, substr_count($response->getContent(), '<td>Budi</td>'));
    }
}
