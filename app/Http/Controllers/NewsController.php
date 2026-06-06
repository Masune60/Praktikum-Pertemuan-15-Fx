<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Menampilkan detail berita berdasarkan slug.
     */
    public function show($slug)
    {
        // Mengambil berita dengan relasi category, tags, dan user beserta profilenya
        $article = Article::with(['category', 'tags', 'user.profile'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('berita.show', compact('article'));
    }
}
