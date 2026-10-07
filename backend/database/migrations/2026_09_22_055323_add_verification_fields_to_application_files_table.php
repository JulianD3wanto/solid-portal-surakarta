<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_files', function (Blueprint $table) {
            $table->string('verification_status')->default('pending')->after('scan_status');
            $table->text('verification_note')->nullable()->after('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('application_files', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'verification_note']);
        });
    }
};
