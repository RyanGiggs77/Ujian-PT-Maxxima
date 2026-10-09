<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_ujian', 'id_matpel', 'tanggal'])]
class Ujian extends Model
{
    protected $table = 'ujian';

    protected $primaryKey = 'id_ujian';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['tanggal' => 'datetime'];
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'id_matpel', 'id_matpel');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'id_ujian', 'id_ujian');
    }
}
