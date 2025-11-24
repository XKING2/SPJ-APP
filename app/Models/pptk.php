<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pptk extends Model
{
    protected $table = 'pptk';
    protected $fillable = ['nama_pptk', 'idinjab_pptk', 'nip_pptk','gol_pptk'];

    public function kwitansis()
    {
        return $this->hasMany(Kwitansi::class, 'id_pptk');
    }
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_pptk');
    }

}
