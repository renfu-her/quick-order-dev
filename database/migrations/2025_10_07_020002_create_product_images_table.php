<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->integer('display_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index(['product_id', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};

