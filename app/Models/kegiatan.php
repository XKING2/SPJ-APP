<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $fillable = ['id_pptk', 'program', 'kegiatan', 'subkegiatan','kasubag'];

    public function pptk()
    {
        return $this->belongsTo(Pptk::class, 'id_pptk');
    }
}
