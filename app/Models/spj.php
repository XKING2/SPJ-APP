<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SPJ extends Model
{
    use HasFactory;

    protected $fillable = [
        'pesanan_id',
        'pemeriksaan_id',
        'penerimaan_id',
        'kwitansi_id',
        'nomor_spj',
        'tanggal_spj',
        'status',
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

    // Relasi ke tabel lain
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class);
    }

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class);
    }

    public function kwitansi()
    {
        return $this->belongsTo(Kwitansi::class);
    }
}
