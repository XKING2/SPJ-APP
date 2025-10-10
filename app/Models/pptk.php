<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pptk extends Model
{
    protected $table = 'pptk';
    protected $fillable = ['subkegiatan', 'nama_pptk', 'jabatan_pptk', 'nip_pptk'];

    public function kwitansis()
    {
        return $this->hasMany(Kwitansi::class, 'id_pptk');
    }
}
