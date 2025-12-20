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
        Schema::table('paper_reviewer', function (Blueprint $table) {
            if (!Schema::hasColumn('paper_reviewer', 'review_file_name')) {
                $table->string('review_file_name')->nullable()->after('review_file');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paper_reviewer', function (Blueprint $table) {
            if (Schema::hasColumn('paper_reviewer', 'review_file_name')) {
                $table->dropColumn('review_file_name');
            }
        });
    }
};
