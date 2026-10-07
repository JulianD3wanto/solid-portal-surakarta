<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'kelurahan_id', 'document_type_id', 'application_number', 'status', 'submitted_data', 'citizen_note', 'officer_note', 'submitted_at', 'completed_at'];

    protected function casts(): array
    {
        return ['submitted_data' => 'array', 'submitted_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function kelurahan(): BelongsTo { return $this->belongsTo(Kelurahan::class); }
    public function documentType(): BelongsTo { return $this->belongsTo(DocumentType::class); }
    public function files(): HasMany { return $this->hasMany(ApplicationFile::class); }
    public function events(): HasMany { return $this->hasMany(ApplicationEvent::class); }
}
