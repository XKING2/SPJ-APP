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
        'user_id',
        'field_name',
        'message',
        'role',
    ];

    /**
     * Relasi ke SPJ.
     * Setiap feedback milik satu SPJ.
     */
    public function spj()
    {
        return $this->belongsTo(Spj::class, 'spj_id');
    }

    /**
     * Relasi ke User (yang memberikan feedback).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope filter berdasarkan role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
