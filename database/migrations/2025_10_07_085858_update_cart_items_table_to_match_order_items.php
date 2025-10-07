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
        Schema::table('cart_items', function (Blueprint $table) {
            // Add missing fields to match OrderItem structure
            $table->string('product_name')->after('product_id');
            $table->decimal('subtotal', 10, 2)->after('unit_price');
            $table->text('special_instructions')->nullable()->after('subtotal');
            
            // Update temperature enum to match OrderItem (add 'regular' option)
            $table->enum('temperature', ['hot', 'cold', 'regular', 'none'])->default('regular')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn(['product_name', 'subtotal', 'special_instructions']);
            
            // Revert temperature enum
            $table->enum('temperature', ['hot', 'cold', 'none'])->default('none')->change();
        });
    }
};
