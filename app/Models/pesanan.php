<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model {
    protected $fillable = ['spj_id','no_surat','nama_pt','alamat_pt','tanggal_diterima','surat_dibuat','nomor_tlp_pt'];

    public function items() {
        return $this->hasMany(PesananItem::class);
    }
    public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'pesanan_id');
    }

    public function penerimaans()
    {
        return $this->hasManyThrough(
            Penerimaan::class, 
            Pemeriksaan::class,
            'pesanan_id',     // Foreign key di tabel pemeriksaans
            'pemeriksaan_id', // Foreign key di tabel penerimaans
            'id',             // Local key di pesanan
            'id'              // Local key di pemeriksaan
        );
    }
}

class PesananItem extends Model {
    protected $fillable = ['pesanan_id','nama_barang','jumlah'];

    public function pesanan() {
        return $this->belongsTo(Pesanan::class);
    }
}


