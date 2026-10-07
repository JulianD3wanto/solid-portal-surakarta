<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { public function up(): void { Schema::create('citizen_profiles', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); $table->foreignId('kelurahan_id')->nullable()->constrained()->nullOnDelete(); $table->string('phone')->nullable(); $table->text('address')->nullable(); $table->timestamps(); }); } public function down(): void { Schema::dropIfExists('citizen_profiles'); } };
