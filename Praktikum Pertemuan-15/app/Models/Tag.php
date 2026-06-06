<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    // Relasi Many-to-Many ke model Article
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}