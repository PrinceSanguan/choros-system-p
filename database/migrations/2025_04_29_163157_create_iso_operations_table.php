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
        if (!Schema::hasTable('iso_operations')) {
            Schema::create('iso_operations', function (Blueprint $table) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iso_operations');
    }
};
