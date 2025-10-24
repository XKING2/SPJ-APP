<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $fillable = [
        'spj_id', 'pesanan_id', 'pemeriksaan_id', 'pekerjaan', 'no_surat', 'surat_dibuat',
        'nama_pihak_kedua', 'jabatan_pihak_kedua',
        'subtotal', 'ppn', 'grandtotal', 'dibulatkan', 'terbilang'
    ];

    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
    }

    public function details()
    {
        return $this->hasMany(PenerimaanDetail::class, 'penerimaan_id');
    }
}

