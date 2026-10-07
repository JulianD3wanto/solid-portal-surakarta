<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_files', function (Blueprint $table) {
            $table->string('path')->nullable()->change();
            $table->string('original_name')->nullable()->change();
            $table->string('mime_type')->nullable()->change();
            $table->unsignedBigInteger('size')->nullable()->change();
            $table->string('sha256', 64)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('application_files', function (Blueprint $table) {
            $table->string('path')->nullable(false)->change();
            $table->string('original_name')->nullable(false)->change();
            $table->string('mime_type')->nullable(false)->change();
            $table->unsignedBigInteger('size')->nullable(false)->change();
            $table->string('sha256', 64)->nullable(false)->change();
        });
    }
};
