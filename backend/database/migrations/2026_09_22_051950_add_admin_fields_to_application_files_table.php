<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_files', function (Blueprint $table) {
            $table->boolean('is_result')->default(false)->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('application_files', function (Blueprint $table) {
            $table->dropColumn('is_result');
        });
    }
};
