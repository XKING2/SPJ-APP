<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pekerjaans extends Model
{
    protected $table = 'pekerjaans';
    protected $fillable = ['spj_id', 'kegiatan_id', 'pekerjaan',];

    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(kwitansi_kegiatan::class, 'kegiatan_id');
    }

    
}
