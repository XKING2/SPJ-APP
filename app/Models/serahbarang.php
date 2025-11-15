<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class serahbarang extends Model
{
    protected $table = 'serah_barang';
    protected $fillable = [
        'spj_id', 'id_plt','id_pihak_kedua','id_pemeriksaan','no_suratsss'
    ];


    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    public function penerimaans()
    {
        return $this->hasMany(Penerimaan::class, 'id_serahbarang');
    }

    public function plt()
    {
        return $this->belongsTo(Plt::class, 'id_plt');
    }

    public function pihak_kedua()
    {
        return $this->belongsTo(Pihakkedua::class, 'id_pihak_kedua');
    }
}
