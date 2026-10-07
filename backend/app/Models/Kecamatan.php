<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kecamatan extends Model { protected $fillable = ['name','postal_code_range']; public function kelurahans() { return $this->hasMany(Kelurahan::class); } }
