<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('paper_authors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paper_id');
            $table->boolean('is_primary')->default(false);
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('organization');
            $table->string('country');
            $table->timestamps();
        });
        
    }

   
    public function down(): void
    {
        Schema::dropIfExists('paper_authors');
    }
};