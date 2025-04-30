<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check if the table exists, and if it does, drop it to recreate
        if (Schema::hasTable('ctg_documents')) {
            Schema::dropIfExists('ctg_documents');
        }

        // Create the table fresh
        Schema::create('ctg_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ctg_id')->constrained('ctgs')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctg_documents');
    }
};
