<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration { public function up(): void { Schema::table('citizen_profiles', function (Blueprint $table) { $table->string('nik', 16)->nullable()->after('user_id')->unique(); }); } public function down(): void { Schema::table('citizen_profiles', function (Blueprint $table) { $table->dropColumn('nik'); }); } };
