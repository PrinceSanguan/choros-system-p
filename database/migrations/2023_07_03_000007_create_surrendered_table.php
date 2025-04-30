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
        if (!Schema::hasTable('surrendered')) {
            Schema::create('surrendered', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('alias')->nullable();
                $table->string('gender');
                $table->string('region');
                $table->string('province');
                $table->string('municipality');
                $table->string('barangay');
                $table->date('date_of_birth')->nullable();
                $table->string('former_group');
                $table->text('remarks')->nullable();
                $table->dateTime('date_surrendered');
                $table->enum('status', ['rehabilitation', 'processing', 'completed'])->default('processing');
                $table->string('photo_path')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surrendered');
    }
};
