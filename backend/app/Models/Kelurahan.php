<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model { protected $fillable = ['kecamatan_id','name','code']; public function kecamatan() { return $this->belongsTo(Kecamatan::class); } public function citizenProfiles() { return $this->hasMany(CitizenProfile::class); } }
