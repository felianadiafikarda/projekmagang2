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
            $table->text('comments_for_author')->nullable()->after('recommendation');
            $table->text('comments_for_editor')->nullable()->after('comments_for_author');
            $table->string('review_file')->nullable()->after('comments_for_editor');
            $table->timestamp('reviewed_at')->nullable()->after('review_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paper_reviewer', function (Blueprint $table) {
            $table->dropColumn(['comments_for_author', 'comments_for_editor', 'review_file', 'reviewed_at']);
        });
    }
};







