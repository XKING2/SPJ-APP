<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SPJ extends Model
{
    use HasFactory;

    protected $table = 'spjs';

    protected $fillable = [
    
        'user_id',
        'status',
        'status2',
        'komentar_kasubag',
        'komentar_bendahara'
    ];

    public function pesanan() 
    {
        return $this->hasOne(Pesanan::class, 'spj_id');
    }

    public function pemeriksaan()
    {
        return $this->hasOne(Pemeriksaan::class, 'spj_id');
    }

    public function penerimaan()
    {
        return $this->hasOne(Penerimaan::class, 'spj_id');
    }

    public function kwitansi() {
        return $this->hasOne(Kwitansi::class, 'spj_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(spj_feedbacks::class, 'spj_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($spj) {
            $spj->pesanan()->delete();
            $spj->pemeriksaan()->delete();
            $spj->penerimaan()->delete();
            $spj->kwitansi()->delete();
        });
    }

}
