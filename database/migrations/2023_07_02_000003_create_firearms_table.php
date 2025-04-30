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
        Schema::create('firearms', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('type');
            $table->string('caliber');
            $table->dateTime('date');
            $table->string('location');
            $table->string('surrendered_by')->nullable();
            $table->text('weapons')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firearms');
    }
};
