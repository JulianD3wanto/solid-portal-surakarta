<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationFile extends Model
{
    use HasFactory;

    protected $fillable = ['application_id', 'uploaded_by', 'disk', 'path', 'original_name', 'mime_type', 'size', 'sha256', 'category', 'scan_status', 'verification_status', 'verification_note', 'is_result'];

    protected function casts(): array
    {
        return ['is_result' => 'boolean'];
    }

    public function application(): BelongsTo { return $this->belongsTo(Application::class); }
    public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
}
