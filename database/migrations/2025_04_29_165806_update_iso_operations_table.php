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
        Schema::table('iso_operations', function (Blueprint $table) {
            if (!Schema::hasColumn('iso_operations', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('iso_operations', 'region')) {
                $table->string('region');
            }
            if (!Schema::hasColumn('iso_operations', 'date')) {
                $table->dateTime('date');
            }
            if (!Schema::hasColumn('iso_operations', 'team_leader')) {
                $table->string('team_leader');
            }
            if (!Schema::hasColumn('iso_operations', 'members')) {
                $table->text('members')->nullable();
            }
            if (!Schema::hasColumn('iso_operations', 'location')) {
                $table->string('location');
            }
            if (!Schema::hasColumn('iso_operations', 'coordinates')) {
                $table->string('coordinates')->nullable();
            }
            if (!Schema::hasColumn('iso_operations', 'location_per_day')) {
                $table->text('location_per_day')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iso_operations', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'region',
                'date',
                'team_leader',
                'members',
                'location',
                'coordinates',
                'location_per_day'
            ]);
        });
    }
};
