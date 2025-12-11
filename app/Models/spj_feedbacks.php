<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class spj_feedbacks extends Model
{
    use HasFactory;

    protected $table = 'spj_feedbacks';

    protected $fillable = [
        'spj_id',
        'section',
        'record_id',
        'field',
        'message',
        'role',
    ];

    public function spj()
    {
        return $this->belongsTo(Spj::class);
    }

    // Relationship dinamis (polymorphic hand-made)
    public function relatedRecord()
    {
        $section = strtolower($this->section);

        return match ($section) {

            // pesanan
            'pesanan' => $this->belongsTo(Pesanan::class, 'record_id'),

            // kwitansi
            'kwitansi' => $this->belongsTo(Kwitansi::class, 'record_id'),

            // pemeriksaan
            'pemeriksaan' => $this->belongsTo(Pemeriksaan::class, 'record_id'),

            // penerimaan
            'penerimaan' => $this->belongsTo(Penerimaan::class, 'record_id'),

            // serah barang
            'serahbarang', 
            'serah_barang' => $this->belongsTo(Serahbarang::class, 'record_id'),

            default => null,
        };
    }

}
