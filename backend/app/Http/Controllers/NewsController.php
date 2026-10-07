<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('news.index', ['news' => News::with('author')->published()->latest('published_at')->paginate(9)]);
    }

    public function show(News $news): View
    {
        abort_unless($news->is_published && $news->published_at?->isPast(), 404);
        return view('news.show', ['news' => $news]);
    }
}
