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
        } else {
            // If the table exists, we'll add any missing columns
            Schema::table('surrendered', function (Blueprint $table) {
                if (!Schema::hasColumn('surrendered', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('surrendered', 'alias')) {
                    $table->string('alias')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'gender')) {
                    $table->string('gender');
                }
                if (!Schema::hasColumn('surrendered', 'region')) {
                    $table->string('region');
                }
                if (!Schema::hasColumn('surrendered', 'province')) {
                    $table->string('province');
                }
                if (!Schema::hasColumn('surrendered', 'municipality')) {
                    $table->string('municipality');
                }
                if (!Schema::hasColumn('surrendered', 'barangay')) {
                    $table->string('barangay');
                }
                if (!Schema::hasColumn('surrendered', 'date_of_birth')) {
                    $table->date('date_of_birth')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'former_group')) {
                    $table->string('former_group');
                }
                if (!Schema::hasColumn('surrendered', 'remarks')) {
                    $table->text('remarks')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'date_surrendered')) {
                    $table->dateTime('date_surrendered');
                }
                if (!Schema::hasColumn('surrendered', 'status')) {
                    $table->enum('status', ['rehabilitation', 'processing', 'completed'])->default('processing');
                }
                if (!Schema::hasColumn('surrendered', 'photo_path')) {
                    $table->string('photo_path')->nullable();
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
