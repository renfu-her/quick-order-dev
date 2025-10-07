<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->enum('temperature', ['hot', 'cold', 'none'])->default('none');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
            
            $table->index(['cart_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};

