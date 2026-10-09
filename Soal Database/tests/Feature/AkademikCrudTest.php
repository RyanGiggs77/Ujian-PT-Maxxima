<?php

namespace Tests\Feature;

use App\Models\MataPelajaran;
use App\Models\Peserta;
use App\Models\Siswa;
use App\Models\Ujian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AkademikCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_crud_preserves_nis_and_supports_100_character_addresses(): void
    {
        $this->get(route('siswa.create'))->assertOk()->assertViewIs('siswa.create');
        $this->post(route('siswa.store'), ['nis' => '001', 'nama' => 'Andi', 'alamat' => str_repeat('A', 100)])
            ->assertRedirect(route('siswa.index'));
        $this->get(route('siswa.index'))->assertOk()->assertViewIs('siswa.index')->assertSee('Andi');
        $this->get(route('siswa.edit', '001'))->assertOk()->assertViewIs('siswa.edit')->assertSee('Andi');
        $this->put(route('siswa.update', '001'), ['nis' => '999', 'nama' => 'Andi Baru', 'alamat' => 'Bandung'])
            ->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswa', ['nis' => '001', 'nama' => 'Andi Baru']);
        $this->assertDatabaseMissing('siswa', ['nis' => '999']);
        $this->delete(route('siswa.destroy', '001'))->assertRedirect(route('siswa.index'));
        $this->assertDatabaseMissing('siswa', ['nis' => '001']);
    }

    public function test_mata_pelajaran_crud(): void
    {
        $this->get(route('mata-pelajaran.create'))->assertOk()->assertViewIs('mata-pelajaran.create');
        $this->post(route('mata-pelajaran.store'), ['nama_matpel' => 'Matematika'])->assertRedirect(route('mata-pelajaran.index'));
        $mataPelajaran = MataPelajaran::firstOrFail();
        $this->get(route('mata-pelajaran.index'))->assertOk()->assertViewIs('mata-pelajaran.index')->assertSee('Matematika');
        $this->get(route('mata-pelajaran.edit', $mataPelajaran))->assertOk()->assertViewIs('mata-pelajaran.edit');
        $this->put(route('mata-pelajaran.update', $mataPelajaran), ['nama_matpel' => 'Fisika'])->assertRedirect(route('mata-pelajaran.index'));
        $this->assertDatabaseHas('mata_pelajaran', ['nama_matpel' => 'Fisika']);
        $this->delete(route('mata-pelajaran.destroy', $mataPelajaran))->assertRedirect(route('mata-pelajaran.index'));
        $this->assertDatabaseCount('mata_pelajaran', 0);
    }

    public function test_ujian_crud_and_zero_participant_count(): void
    {
        $matpel = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $data = ['nama_ujian' => 'UTS', 'id_matpel' => $matpel->id_matpel, 'tanggal' => '2026-10-12', 'waktu' => '08:00'];
        $this->get(route('ujian.create'))->assertOk()->assertViewIs('ujian.create')->assertSee('Matematika')
            ->assertSee('type="date"', false);
        $this->post(route('ujian.store'), $data)->assertRedirect(route('ujian.index'));
        $ujian = Ujian::firstOrFail();
        $this->get(route('ujian.index'))->assertOk()->assertViewIs('ujian.index')->assertViewHas('daftarUjian', fn ($rows) => $rows->first()->peserta_count === 0);
        $this->get(route('ujian.edit', $ujian))->assertOk()->assertViewIs('ujian.edit')->assertSee('type="date"', false)
            ->assertSee('2026-10-12')->assertSee('08:00');
        $this->put(route('ujian.update', $ujian), [...$data, 'nama_ujian' => 'UAS'])->assertRedirect(route('ujian.index'));
        $this->assertDatabaseHas('ujian', ['nama_ujian' => 'UAS', 'tanggal' => '2026-10-12 08:00:00']);
        $this->delete(route('ujian.destroy', $ujian))->assertRedirect(route('ujian.index'));
        $this->assertDatabaseCount('ujian', 0);
    }

    public function test_peserta_crud_updates_kelulusan_and_can_clear_grade(): void
    {
        [$siswa, $ujian] = $this->academicData();
        $data = ['nis' => $siswa->nis, 'id_ujian' => $ujian->id_ujian, 'nilai' => ''];
        $this->get(route('peserta.create'))->assertOk()->assertViewIs('peserta.create')->assertSee('Andi')->assertSee('Matematika');
        $this->post(route('peserta.store'), $data)->assertRedirect(route('peserta.index'));
        $peserta = Peserta::firstOrFail();
        $this->assertNull($peserta->nilai);
        $this->get(route('peserta.index'))->assertOk()->assertViewIs('peserta.index')->assertSee('Belum dinilai');
        $this->get(route('peserta.edit', $peserta))->assertOk()->assertViewIs('peserta.edit');
        $this->put(route('peserta.update', $peserta), [...$data, 'nilai' => 75])->assertRedirect(route('peserta.index'));
        $this->get(route('peserta.index'))->assertOk()->assertSee('Lulus');
        $this->put(route('peserta.update', $peserta), [...$data, 'nilai' => 74.99])->assertRedirect(route('peserta.index'));
        $this->get(route('peserta.index'))->assertOk()->assertSee('Tidak lulus');
        $this->put(route('peserta.update', $peserta), $data)->assertRedirect(route('peserta.index'));
        $this->assertNull($peserta->fresh()->nilai);
        $this->delete(route('peserta.destroy', $peserta))->assertRedirect(route('peserta.index'));
        $this->assertDatabaseCount('peserta', 0);
    }

    public function test_duplicate_students_and_participants_are_rejected(): void
    {
        [$siswa, $ujian] = $this->academicData();
        $this->post(route('siswa.store'), ['nis' => $siswa->nis, 'nama' => 'Duplikat', 'alamat' => 'Bandung'])->assertSessionHasErrors('nis');
        $data = ['nis' => $siswa->nis, 'id_ujian' => $ujian->id_ujian, 'nilai' => 80];
        $this->post(route('peserta.store'), $data)->assertRedirect(route('peserta.index'));
        $this->post(route('peserta.store'), $data)->assertSessionHasErrors('nis');
        $this->assertDatabaseCount('peserta', 1);
        $this->put(route('peserta.update', Peserta::firstOrFail()), $data)->assertSessionHasNoErrors();
    }

    #[DataProvider('invalidGrades')]
    public function test_invalid_grades_are_rejected(string $nilai): void
    {
        [$siswa, $ujian] = $this->academicData();
        $this->post(route('peserta.store'), ['nis' => $siswa->nis, 'id_ujian' => $ujian->id_ujian, 'nilai' => $nilai])
            ->assertSessionHasErrors('nilai');
        $this->assertDatabaseCount('peserta', 0);
    }

    public function test_references_and_required_fields_are_validated(): void
    {
        $this->post(route('siswa.store'), ['nis' => 'bad/nis', 'nama' => '', 'alamat' => str_repeat('A', 101)])
            ->assertSessionHasErrors(['nis', 'nama', 'alamat']);
        $this->post(route('mata-pelajaran.store'), ['nama_matpel' => ''])->assertSessionHasErrors('nama_matpel');
        $this->post(route('ujian.store'), ['nama_ujian' => 'UTS', 'id_matpel' => 999, 'tanggal' => 'invalid'])
            ->assertSessionHasErrors(['id_matpel', 'tanggal']);
        $this->post(route('peserta.store'), ['nis' => '999', 'id_ujian' => 999, 'nilai' => 80])
            ->assertSessionHasErrors(['nis', 'id_ujian']);
    }

    public function test_used_records_cannot_be_deleted(): void
    {
        [$siswa, $ujian] = $this->academicData();
        Peserta::create(['nis' => $siswa->nis, 'id_ujian' => $ujian->id_ujian, 'nilai' => 80]);
        $this->from(route('siswa.index'))->delete(route('siswa.destroy', $siswa))->assertSessionHasErrors('database');
        $this->from(route('ujian.index'))->delete(route('ujian.destroy', $ujian))->assertSessionHasErrors('database');
        $this->from(route('mata-pelajaran.index'))->delete(route('mata-pelajaran.destroy', $ujian->mataPelajaran))->assertSessionHasErrors('database');
        $this->assertDatabaseCount('siswa', 1);
        $this->assertDatabaseCount('ujian', 1);
        $this->assertDatabaseCount('mata_pelajaran', 1);
    }

    public function test_missing_records_return_404(): void
    {
        foreach (['siswa', 'mata-pelajaran', 'ujian', 'peserta'] as $resource) {
            $this->get(route($resource.'.edit', '999'))->assertNotFound();
        }
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidGrades(): array
    {
        return ['negatif' => ['-1'], 'lebih dari 100' => ['101'], 'terlalu presisi' => ['75.001'], 'bukan angka' => ['abc']];
    }

    /**
     * @return array{0: Siswa, 1: Ujian}
     */
    private function academicData(): array
    {
        $siswa = Siswa::create(['nis' => '001', 'nama' => 'Andi', 'alamat' => 'Bandung']);
        $matpel = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $ujian = $matpel->ujian()->create(['nama_ujian' => 'UTS', 'tanggal' => '2026-10-12 08:00:00']);

        return [$siswa, $ujian];
    }
}
