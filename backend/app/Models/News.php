<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['author_id', 'title', 'slug', 'excerpt', 'body', 'image_path', 'published_at', 'is_published'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_published' => 'boolean'];
    }

    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }

    /**
     * Sanitize rich-text body from the admin editor before persisting.
     */
    public function setBodyAttribute($value): void
    {
        $allowed = '<p><br><h1><h2><h3><h4><h5><h6><strong><b><em><i><u><s><strike><ol><ul><li><blockquote><a><span><div><pre><code><u>';
        $clean = strip_tags((string) $value, $allowed);
        $clean = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean);
        $clean = preg_replace('/(href|src)\s*=\s*"\s*javascript:[^"]*"/i', '$1="#"', $clean);
        $clean = preg_replace('/(href|src)\s*=\s*\'\s*javascript:[^\']*\'/i', '$1="#"', $clean);
        $this->attributes['body'] = $clean;
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now());
    }
}
