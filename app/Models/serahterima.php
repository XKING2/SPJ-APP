<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class serahterima extends Model
{
    protected $fillable = [
        'no_surat', 'nama_pt', 'alamat_pt', 
        'tanggal_diterima', 'surat_dibuat', 'nomor_tlp_pt', 
        'nama_barang','jumlah'
    ];
}
