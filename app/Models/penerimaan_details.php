<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penerimaan_details extends Model
{
    protected $fillable = [
        'penerimaan_id', 'pesanan_item_id', 'nama_barang', 'jumlah', 'satuan', 'harga_satuan', 'total'
    ];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class);
    }
}
