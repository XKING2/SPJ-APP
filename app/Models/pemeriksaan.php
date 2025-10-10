<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pemeriksaan extends Model
{
    protected $fillable = [
        'pesanan_id','spj_id',
        'hari_diterima', 'tanggals_diterima', 'bulan_diterima', 
        'tahun_diterima', 'nama_pihak_kedua', 'jabatan_pihak_kedua', 
        'alamat_pihak_kedua','pekerjaan'
    ];
    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    // Relasi ke SPJ
    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    // Relasi ke Penerimaan (jika ada)
    public function penerimaans()
    {
        return $this->hasMany(Penerimaan::class, 'pemeriksaan_id');
    }

}
