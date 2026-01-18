<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{

    protected $fillable = [
        'pesanan_id', 'spj_id','no_suratssss','id_pekerjaan',
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

    public function serahbarang()
    {
        return $this->hasMany(Penerimaan::class, 'id_pemeriksaan');
    }

    public function plt()
    {
        return $this->belongsTo(Plt::class, 'id_plt');
    }

    public function pihak_kedua()
    {
        return $this->belongsTo(Pihakkedua::class, 'id_pihak_kedua');
    }

    public function pekerjaans()
    {
        return $this->belongsTo(pekerjaans::class, 'id_pekerjaan');
    }




}
