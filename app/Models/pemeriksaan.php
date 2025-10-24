<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{

    protected $fillable = [
        'pesanan_id', 'spj_id', 'id_plt',
        'hari_diterima', 'tanggals_diterima', 'bulan_diterima',
        'tahun_diterima', 'nama_pihak_kedua', 'jabatan_pihak_kedua',
        'alamat_pihak_kedua', 'pekerjaan'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    public function penerimaans()
    {
        return $this->hasMany(Penerimaan::class, 'pemeriksaan_id');
    }

    public function plt()
    {
        return $this->belongsTo(Plt::class, 'id_plt');
    }




}
