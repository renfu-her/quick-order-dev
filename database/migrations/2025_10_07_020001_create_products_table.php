<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Required base price
            $table->decimal('special_price', 10, 2)->nullable();
            $table->decimal('cold_price', 10, 2)->nullable();
            $table->decimal('hot_price', 10, 2)->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_available', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

