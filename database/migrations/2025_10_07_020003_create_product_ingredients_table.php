<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('ingredient_name');
            $table->boolean('is_available')->default(true);
            $table->decimal('extra_price', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ingredients');
    }
};

