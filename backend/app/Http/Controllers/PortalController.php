<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\News;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'services' => DocumentType::query()->where('is_active', true)->latest()->get(),
            'news' => News::with('author')->published()->latest('published_at')->limit(9)->get(),
        ]);
    }

    public function services(): View
    {
        return view('services.index', [
            'services' => DocumentType::query()->where('is_active', true)->latest()->get(),
        ]);
    }

    public function service(DocumentType $documentType): View
    {
        abort_unless($documentType->is_active, 404);
        return view('services.show', ['service' => $documentType]);
    }
}
