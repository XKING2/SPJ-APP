<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class kwitansi extends Model
{
    use HasFactory;

   protected $fillable = [
        'spj_id', 'id_pptk', 'id_kegiatan', 'no_rekening', 'no_rekening_tujuan',
        'nama_bank', 'penerima_kwitansi', 'telah_diterima_dari',
        'jumlah_nominal', 'uang_terbilang', 'jabatan_penerima', 'npwp', 'pembayaran'
    ];


    public function spj()
    {
        return $this->belongsTo(SPJ::class, 'spj_id');
    }

    public function pptk()
    {
        return $this->belongsTo(pptk::class, 'id_pptk');
    }

    public function spjs()
    {
        return $this->belongsTo(SPJ::class, 'spj_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(kegiatan::class, 'id_kegiatan');
    }


}
