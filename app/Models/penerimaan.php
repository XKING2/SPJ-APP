<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penerimaan extends Model
{
    protected $fillable = [
        'spj_id','pemeriksaan_id', 'pesanan_id', 'pekerjaan', 'no_surat', 'surat_dibuat',
        'nama_pihak_kedua', 'jabatan_pihak_kedua',
        'subtotal', 'ppn', 'grandtotal', 'dibulatkan', 'terbilang'
    ];

    public function details()
    {
        return $this->hasMany(penerimaan_details::class);
    }

    public function pesanan() {
        return $this->belongsTo(Pesanan::class);
    }

    public function pemeriksaan() {
        return $this->belongsTo(Pemeriksaan::class);
    }
}
