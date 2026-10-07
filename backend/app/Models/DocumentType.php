<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name', 'description', 'requirements', 'processing_time', 'icon', 'is_active'];

    protected function casts(): array
    {
        return ['requirements' => 'array', 'is_active' => 'boolean'];
    }

    public function templates(): HasMany
    {
        return $this->hasMany(DocumentTemplate::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
