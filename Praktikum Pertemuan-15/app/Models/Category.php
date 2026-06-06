<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // Relasi One-to-Many ke model Article
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}