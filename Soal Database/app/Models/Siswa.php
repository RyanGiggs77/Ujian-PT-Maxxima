<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nis', 'nama', 'alamat'])]
class Siswa extends Model
{
    protected $table = 'siswa';

    protected $primaryKey = 'nis';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'nis', 'nis');
    }
}
