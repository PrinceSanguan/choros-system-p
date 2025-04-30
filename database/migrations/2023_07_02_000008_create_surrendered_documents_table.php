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
        if (!Schema::hasTable('surrendered_documents')) {
            Schema::create('surrendered_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('surrendered_id')->constrained('surrendered')->onDelete('cascade');
                $table->string('document_type');
                $table->string('file_path');
                $table->string('filename');
                $table->string('original_filename');
                $table->string('mime_type');
                $table->integer('file_size');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surrendered_documents');
    }
};
