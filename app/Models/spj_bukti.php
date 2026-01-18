<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class spj_bukti extends Model
{
    protected $table = 'spj_buktis';
    protected $fillable = [
        'spj_id',
        'file_path',
        'file_name',
        'file_type',
        'jenis_bukti',
        'keterangan',
        'uploaded_by'
    ];

    public function spj()
    {
        return $this->belongsTo(Spj::class);
    }
}
