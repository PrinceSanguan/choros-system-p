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
        Schema::create('i_s_o_operations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region');
            $table->dateTime('date');
            $table->string('team_leader');
            $table->text('members')->nullable();
            $table->string('location');
            $table->string('coordinates')->nullable();
            $table->text('location_per_day')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_s_o_operations');
    }
};
