<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitizenProfile extends Model { protected $fillable = ['user_id','nik','kelurahan_id','phone','address']; public function user() { return $this->belongsTo(User::class); } public function kelurahan() { return $this->belongsTo(Kelurahan::class); } }
