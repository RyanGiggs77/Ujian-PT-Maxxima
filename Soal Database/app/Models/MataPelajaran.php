<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_matpel'])]
class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $primaryKey = 'id_matpel';

    public $timestamps = false;

    public function ujian(): HasMany
    {
        return $this->hasMany(Ujian::class, 'id_matpel', 'id_matpel');
    }
}
