<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    // Pengirim pesan
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Penerima pesan
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
