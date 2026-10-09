<?php

namespace Tests\Feature;

use App\Models\MataPelajaran;
use App\Models\Peserta;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AkademikModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_can_save_and_load_academic_relationships(): void
    {
        $siswa = Siswa::create([
            'nis' => '001',
            'nama' => 'Andi',
            'alamat' => str_repeat('A', 100),
        ]);
        $mataPelajaran = MataPelajaran::create(['nama_matpel' => 'Matematika']);
        $ujian = $mataPelajaran->ujian()->create([
            'nama_ujian' => 'UTS Matematika',
            'tanggal' => '2026-10-12 08:00:00',
        ]);
        $peserta = $ujian->peserta()->create(['nis' => $siswa->nis, 'nilai' => 75]);
        $peserta = Peserta::with(['siswa', 'ujian.mataPelajaran'])->findOrFail($peserta->id);

        $this->assertSame('001', $peserta->siswa->getKey());
        $this->assertTrue($peserta->ujian->is($ujian));
        $this->assertTrue($peserta->ujian->mataPelajaran->is($mataPelajaran));
        $this->assertTrue($siswa->peserta->first()->is($peserta));
        $this->assertTrue($mataPelajaran->ujian->first()->is($ujian));
        $this->assertSame('2026-10-12 08:00:00', $peserta->ujian->tanggal->format('Y-m-d H:i:s'));
        $this->assertSame('75.00', $peserta->nilai);
        $this->assertSame('Lulus', $peserta->status_kelulusan);

        $peserta->update(['nilai' => 60]);
        $this->assertSame('Tidak lulus', $peserta->fresh()->status_kelulusan);

        $peserta->update(['nilai' => null]);
        $this->assertSame('Belum dinilai', $peserta->fresh()->status_kelulusan);
    }

    #[DataProvider('kelulusanCases')]
    public function test_kelulusan_uses_fixed_kkm_and_distinguishes_missing_grades(?string $nilai, string $expectedStatus): void
    {
        $peserta = new Peserta(['nilai' => $nilai]);

        $this->assertSame($expectedStatus, $peserta->status_kelulusan);
    }

    /**
     * @return array<string, array{0: ?string, 1: string}>
     */
    public static function kelulusanCases(): array
    {
        return [
            'belum dinilai' => [null, 'Belum dinilai'],
            'nilai nol' => ['0', 'Tidak lulus'],
            'di bawah KKM' => ['74.99', 'Tidak lulus'],
            'tepat KKM' => ['75', 'Lulus'],
            'di atas KKM' => ['75.01', 'Lulus'],
            'nilai maksimum' => ['100', 'Lulus'],
        ];
    }
}
