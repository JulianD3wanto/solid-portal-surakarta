<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitizenNotification extends Model
{
    protected $fillable = ['user_id', 'application_id', 'title', 'message', 'link', 'is_read'];

    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function scopeVisibleFor(Builder $query, $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public static function notifyUser(int $userId, Application $application, string $title, string $message, ?string $link = null): self
    {
        return static::create([
            'user_id' => $userId,
            'application_id' => $application->id,
            'title' => $title,
            'message' => $message,
            'link' => $link ?? '/dashboard',
        ]);
    }
}
