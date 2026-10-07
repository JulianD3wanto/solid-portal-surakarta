<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $fillable = ['application_id', 'kelurahan_id', 'title', 'message', 'link', 'is_read'];

    protected function casts(): array
    {
        return ['is_read' => 'boolean'];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Visible to super admins (everything) and to officers of the matching kelurahan
     * (or global notifications when kelurahan_id is null).
     */
    public function scopeVisibleFor(Builder $query, $user): Builder
    {
        if ($user->role === 'admin') {
            return $query;
        }

        $kelurahanId = $user->citizenProfile?->kelurahan_id;

        return $query->where(function (Builder $q) use ($kelurahanId) {
            $q->whereNull('kelurahan_id');
            if ($kelurahanId) {
                $q->orWhere('kelurahan_id', $kelurahanId);
            }
        });
    }

    public static function notifyNewApplication(Application $application, ?string $verb = null): self
    {
        $citizen = $application->user;
        $verb ??= 'mengajukan';

        return static::create([
            'application_id' => $application->id,
            'kelurahan_id' => $application->kelurahan_id,
            'title' => 'Permohonan baru',
            'message' => $citizen->name.' '.$verb.' '.$application->documentType->name.' ('.$application->application_number.').',
            'link' => '/admin/permohonan/'.$application->id,
        ]);
    }
}
