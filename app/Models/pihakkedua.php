<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pihakkedua extends Model
{
    protected $table = 'pihak_kedua';
    protected $fillable = ['nama_pihak_kedua', 'jabatan_pihak_kedua', 'nip_pihak_kedua','gol_pihak_kedua'];

    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_pihak_kedua');
    }
}
