<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'spj_id',
        'pesanan_id',
        'no_surat',
        'nama_pt',
        'alamat_pt',
        'tanggal_diterima',
        'surat_dibuat',
        'nomor_tlp_pt'
    ];

    public function items() {
        return $this->hasMany(PesananItem::class);
    }

    public function spj() {
        return $this->hasOne(Spj::class, 'pesanan_id');
    }

    public function pemeriksaans() {
        return $this->hasMany(Pemeriksaan::class, 'pesanan_id');
    }

    public function penerimaans() {
        return $this->hasManyThrough(
            Penerimaan::class,
            Pemeriksaan::class,
            'pesanan_id',
            'pemeriksaan_id',
            'id',
            'id'
        );
    }
}
