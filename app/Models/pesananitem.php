<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananItem extends Model
{
    protected $fillable = ['pesanan_id', 'nama_barang', 'jumlah'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function penerimaanDetail()
    {
        return $this->hasOne(PenerimaanDetail::class, 'pesanan_item_id');
    }

    protected static function booted()
    {
        static::deleting(function ($item) {
            // Hapus hanya detail yang terkait langsung
            if ($item->penerimaanDetail) {
                $item->penerimaanDetail->delete();
            }
        });
    }


}

