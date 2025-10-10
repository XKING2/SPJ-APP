<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SPJ extends Model
{
    use HasFactory;

    protected $table = 'spjs';

    protected $fillable = [
        'pesanan_id',
        'pemeriksaan_id',
        'penerimaan_id',
        'kwitansi_id',
        'user_id',
        'nomor_spj',
        'tanggal_spj',
        'status',
        'status2',
        'komentar_kasubag',
        'file_path',
        'nama_pt_snapshot',
        'nama_pemesan_snapshot',
        'pihak_kedua_snapshot',
        'jabatan_pihak_kedua_snapshot',
        'total_snapshot',
        'hasil_pemeriksaan_snapshot',
        'pembayaran_snapshot',
        'terbilang_snapshot',
    ];

    public function pesanan() {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function pemeriksaan() {
        return $this->hasOne(Pemeriksaan::class, 'spj_id');
    }

    public function penerimaan() {
        return $this->hasOne(Penerimaan::class, 'spj_id');
    }

    public function kwitansi() {
        return $this->hasOne(Kwitansi::class, 'spj_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
