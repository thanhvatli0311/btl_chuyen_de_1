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
        Schema::table('discount_codes', function (Blueprint $table) {
            // Thêm cột minimum_order_amount nếu chưa có
            if (!Schema::hasColumn('discount_codes', 'minimum_order_amount')) {
                $table->decimal('minimum_order_amount', 12, 2)->nullable()->after('value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->dropColumn('minimum_order_amount');
        });
    }
};
