<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $fillable = ['user_id', 'category_id', 'title', 'slug', 'content', 'image'];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::deleting(function ($article) {
            // Hapus gambar fisik dari penyimpanan saat model dihapus
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
        });
    }

    // Relasi Many-to-One ke model Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi Many-to-One ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relasi Many-to-Many ke model Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}