<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class nosurat extends Model
{
    protected $table = 'no_surat';
    protected $fillable = ['no_awal', 'nama_dinas', 'tahun',];
}
