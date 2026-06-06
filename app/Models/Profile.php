<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'bio', 'avatar', 'phone'];

    // Relasi One-to-One ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}