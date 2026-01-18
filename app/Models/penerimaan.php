<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    protected $table = 'penerimaans';
    protected $fillable = [
        'spj_id', 'pesanan_id', 'id_serahbarang', 'no_surat', 'surat_dibuat',
        'nama_pihak_kedua', 'jabatan_pihak_kedua',
        'subtotal', 'ppn','pph', 'grandtotal', 'dibulatkan', 'terbilang'
    ];

    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function serahbarang()
    {
        return $this->belongsTo(serahbarang::class, 'id_serahbarang');
    }

    public function details()
    {
        return $this->hasMany(PenerimaanDetail::class, 'penerimaan_id');
    }

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan');
    }
}

