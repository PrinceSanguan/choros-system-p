<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check if the table exists
        if (Schema::hasTable('surrendered')) {
            // Check if the dob column exists before trying to rename it
            if (Schema::hasColumn('surrendered', 'dob')) {
                Schema::table('surrendered', function (Blueprint $table) {
                    $table->renameColumn('dob', 'date_of_birth');
                });
            }

            // Check if status column exists before trying to modify it
            if (Schema::hasColumn('surrendered', 'status')) {
                // Try/catch block to handle if the column doesn't have the right type
                try {
                    DB::statement("ALTER TABLE surrendered MODIFY COLUMN status ENUM('rehabilitation', 'processing', 'completed') DEFAULT 'processing'");
                } catch (\Exception $e) {
                    // Create the column if modifying fails
                    Schema::table('surrendered', function (Blueprint $table) {
                        if (!Schema::hasColumn('surrendered', 'status')) {
                            $table->enum('status', ['rehabilitation', 'processing', 'completed'])->default('processing');
                        }
                    });
                }
            } else {
                // Create the status column if it doesn't exist
                Schema::table('surrendered', function (Blueprint $table) {
                    $table->enum('status', ['rehabilitation', 'processing', 'completed'])->default('processing');
                });
            }

            // Drop columns if they exist
            Schema::table('surrendered', function (Blueprint $table) {
                if (Schema::hasColumn('surrendered', 'alias')) {
                    $table->dropColumn('alias');
                }
                if (Schema::hasColumn('surrendered', 'gender')) {
                    $table->dropColumn('gender');
                }
                if (Schema::hasColumn('surrendered', 'province')) {
                    $table->dropColumn('province');
                }
                if (Schema::hasColumn('surrendered', 'municipality')) {
                    $table->dropColumn('municipality');
                }
                if (Schema::hasColumn('surrendered', 'barangay')) {
                    $table->dropColumn('barangay');
                }
                if (Schema::hasColumn('surrendered', 'address')) {
                    $table->dropColumn('address');
                }
                if (Schema::hasColumn('surrendered', 'pob')) {
                    $table->dropColumn('pob');
                }
                if (Schema::hasColumn('surrendered', 'location')) {
                    $table->dropColumn('location');
                }
                if (Schema::hasColumn('surrendered', 'remarks')) {
                    $table->dropColumn('remarks');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only proceed if table exists
        if (Schema::hasTable('surrendered')) {
            // Restore original column names if date_of_birth exists
            if (Schema::hasColumn('surrendered', 'date_of_birth')) {
                Schema::table('surrendered', function (Blueprint $table) {
                    $table->renameColumn('date_of_birth', 'dob');
                });
            }

            // Restore original status options if status column exists
            if (Schema::hasColumn('surrendered', 'status')) {
                try {
                    DB::statement("ALTER TABLE surrendered MODIFY COLUMN status ENUM('active', 'processing', 'rehabilitation', 'reintegrated', 'deceased') DEFAULT 'processing'");
                } catch (\Exception $e) {
                    // If modification fails, recreate the column
                    Schema::table('surrendered', function (Blueprint $table) {
                        $table->dropColumn('status');
                        $table->enum('status', ['active', 'processing', 'rehabilitation', 'reintegrated', 'deceased'])->default('processing');
                    });
                }
            }

            // Add back columns if they don't exist
            Schema::table('surrendered', function (Blueprint $table) {
                if (!Schema::hasColumn('surrendered', 'alias')) {
                    $table->string('alias')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'gender')) {
                    $table->string('gender')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'province')) {
                    $table->string('province')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'municipality')) {
                    $table->string('municipality')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'barangay')) {
                    $table->string('barangay')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'address')) {
                    $table->string('address')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'pob')) {
                    $table->string('pob')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'location')) {
                    $table->string('location')->nullable();
                }
                if (!Schema::hasColumn('surrendered', 'remarks')) {
                    $table->text('remarks')->nullable();
                }
            });
        }
    }
};
