<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class kwitansi extends Model
{
    use HasFactory;

    protected $fillable = [
        'spj_id', // ✅ tambahkan ini
        'no_rekening',
        'id_pptk',
        'no_rekening_tujuan', 
        'nama_bank', 
        'penerima_kwitansi', 
        'sub_kegiatan', 
        'telah_diterima_dari', 
        'jumlah_nominal',
        'uang_terbilang',
        'jabatan_penerima',
        'npwp',
        'pembayaran',
    ];

    public function pptk()
    {
        return $this->belongsTo(pptk::class, 'id_pptk');
    }

    public function spjs()
    {
        return $this->belongsTo(SPJ::class, 'spj_id');
    }

}
