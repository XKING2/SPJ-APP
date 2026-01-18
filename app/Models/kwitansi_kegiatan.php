<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kwitansi_kegiatan extends Model
{
    protected $table = 'kegiatan_kwitansis';
    protected $fillable = ['kegiatan_id','nama_kegiatan'];
}
