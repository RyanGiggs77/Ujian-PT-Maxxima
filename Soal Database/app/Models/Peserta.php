<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_ujian', 'nis', 'nilai'])]
class Peserta extends Model
{
    public const KKM = 75;

    protected $table = 'peserta';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['nilai' => 'decimal:2'];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'id_ujian', 'id_ujian');
    }

    /**
     * @return Attribute<string, never>
     */
    protected function statusKelulusan(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->nilai === null
                ? 'Belum dinilai'
                : ($this->nilai >= self::KKM ? 'Lulus' : 'Tidak lulus'),
        );
    }
}
