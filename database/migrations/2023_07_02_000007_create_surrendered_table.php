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
                $table->date('date_surrendered');
                $table->string('region');
                $table->string('province');
                $table->string('municipality');
                $table->string('barangay');
                $table->text('remarks')->nullable();
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
