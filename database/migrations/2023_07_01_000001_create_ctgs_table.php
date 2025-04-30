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
        Schema::create('ctgs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region');
            $table->string('address');
            $table->string('pob')->nullable();
            $table->date('dob')->nullable();
            $table->string('affiliated_front');
            $table->dateTime('last_seen')->nullable();
            $table->enum('status', ['active', 'neutralized', 'surrendered', 'deceased'])->default('active');
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctgs');
    }
};
