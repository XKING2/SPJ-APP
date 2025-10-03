<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class kwitansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_rekening', 'no_rekening_tujuan', 'nama_bank', 
        'penerima_kwitansi', 'sub_kegiatan', 'telah_diterima_dari', 
        'jumlah_nominal','uang_terbilang','jabatan_penerima',
        'npwp','nama_pt','pembayaran',
    ];

}
