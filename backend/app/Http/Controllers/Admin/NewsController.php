<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View { return view('admin.news.index', ['news' => News::latest()->get()]); }
    public function create(): View { return view('admin.news.form', ['news' => new News]); }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($data['title']);
        if ($request->hasFile('image')) $data['image_path'] = $request->file('image')->store('news', 'public');
        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(News $news): View { return view('admin.news.form', compact('news')); }

    public function update(Request $request, News $news): RedirectResponse
    {
        $data = $this->validated($request, $news);
        $data['slug'] = $this->uniqueSlug($data['title'], $news);
        if ($request->hasFile('image')) {
            if ($news->image_path) Storage::disk('public')->delete($news->image_path);
            $data['image_path'] = $request->file('image')->store('news', 'public');
        }
        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        if ($news->image_path) Storage::disk('public')->delete($news->image_path);
        $news->delete();
        return back()->with('success', 'Berita berhasil dihapus.');
    }

    private function validated(Request $request, ?News $news = null): array
    {
        return $request->validate(['title' => ['required', 'string', 'max:200'], 'excerpt' => ['required', 'string', 'max:500'], 'body' => ['required', 'string', 'max:100000'], 'published_at' => ['nullable', 'date'], 'is_published' => ['nullable', 'boolean'], 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']]) + ['is_published' => $request->boolean('is_published')];
    }

    private function uniqueSlug(string $value, ?News $news = null): string
    {
        $base = Str::slug($value); $slug = $base; $i = 1;
        while (News::where('slug', $slug)->when($news, fn ($q) => $q->where('id', '!=', $news->id))->exists()) $slug = $base.'-'.++$i;
        return $slug;
    }
}
