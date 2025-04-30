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
                $table->string('file_path');
                $table->string('file_name')->nullable();
                $table->string('file_type')->nullable();
                $table->timestamps();
            });
        } else {
            // If the table exists, we'll add any missing columns
            Schema::table('surrendered_documents', function (Blueprint $table) {
                if (!Schema::hasColumn('surrendered_documents', 'file_name')) {
                    $table->string('file_name')->nullable();
                }
                if (!Schema::hasColumn('surrendered_documents', 'file_type')) {
                    $table->string('file_type')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop the table in reverse migration since it may have been created by another migration
    }
};
