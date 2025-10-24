<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanDetail extends Model
{
    protected $fillable = [
        'penerimaan_id',
        'pesanan_item_id',
        'satuan',
        'harga_satuan',
        'total'
    ];

    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }

    public function pesananItem()
    {
        return $this->belongsTo(PesananItem::class, 'pesanan_item_id');
    }
}

