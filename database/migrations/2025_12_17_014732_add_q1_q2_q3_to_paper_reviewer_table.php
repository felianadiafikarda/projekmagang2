<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('paper_reviewer', function (Blueprint $table) {
            if (!Schema::hasColumn('paper_reviewer', 'Q1')) {
                $table->string('Q1')->nullable();
                $table->string('Q2')->nullable();
                $table->string('Q3')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('paper_reviewer', function (Blueprint $table) {
            $table->dropColumn(['Q1','Q2','Q3']);
        });
    }
};

