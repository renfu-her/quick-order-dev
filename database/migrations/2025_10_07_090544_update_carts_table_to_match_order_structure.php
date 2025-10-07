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
        Schema::table('carts', function (Blueprint $table) {
            // Add fields to match Order structure
            $table->decimal('subtotal', 10, 2)->default(0)->after('session_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal');
            $table->decimal('total_amount', 10, 2)->default(0)->after('discount_amount');
            $table->enum('status', ['active', 'abandoned', 'converted'])->default('active')->after('total_amount');
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Remove added fields
            $table->dropColumn(['subtotal', 'discount_amount', 'total_amount', 'status', 'notes']);
        });
    }
};
