<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('judul');
            $table->text('abstrak')->nullable();
            $table->string('keywords');
            $table->text('paper_references')->nullable();
            $table->enum('status', [
                'submitted',
                'in_review',
                'accept_with_review',
                'revised',
                'accepted',
                'rejected',
            ])->default('submitted');
            $table->string('file_path');          
            $table->timestamps();
        });
        
    }

    
    public function down(): void
    {
        Schema::dropIfExists('papers');
    }
};