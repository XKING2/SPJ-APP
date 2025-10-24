<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class plt extends Model
{
    protected $table = 'plt';
    protected $fillable = ['nama_pihak_pertama', 'jabatan_pihak_pertama', 'nip_pihak_pertama','gol_pihak_pertama'];

        public function pemeriksaans()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_plt');
    }
}
