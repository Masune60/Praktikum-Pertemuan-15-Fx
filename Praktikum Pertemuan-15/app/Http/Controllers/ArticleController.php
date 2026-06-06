<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        // Eager loading categories, users, profiles, dan tags sekaligus untuk efisiensi kueri database
        $articles = Article::with(['category', 'user.profile', 'tags'])->latest()->get();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Maksimal 2MB
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'image.required' => 'Gambar sampul berita wajib diunggah.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.mimes' => 'Format gambar yang diperbolehkan adalah: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        // Proses unggah gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Gambar disimpan di folder storage/app/public/articles
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        $article = Article::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        // Menyimpan relasi Many-to-Many ke tabel pivot article_tag
        if ($request->has('tags')) {
            $article->tags()->attach($request->tags);
        }

        return redirect()->route('articles.index')->with('success', 'Berita dengan gambar sampul berhasil dipublikasikan!');
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $imagePath = $article->image;

        // Jika user mengupload gambar baru
        if ($request->hasFile('image')) {
            // Hapus berkas gambar lama dari penyimpanan server jika ada
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        $article->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . $article->id,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        // Sinkronisasi tag baru di tabel pivot (sync menghapus tag yang tidak dipilih lagi)
        $article->tags()->sync($request->tags ?? []);

        return redirect()->route('articles.index')->with('success', 'Berita berhasil diperbarui!');
    }

    public function destroy(Article $article)
    {
        // Penghapusan gambar fisik kini ditangani oleh model Article (event deleting)
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Berita berhasil dihapus beserta file gambarnya!');
    }
}