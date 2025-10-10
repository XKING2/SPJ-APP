<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananItem extends Model
{
    protected $fillable = ['pesanan_id','nama_barang','jumlah'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
