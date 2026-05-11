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
        // Sửa cột price trong products table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->change();
        });

        // Sửa cột total_price trong orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('total_price')->change();
        });

        // Sửa cột price trong order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại decimal(10,2) nếu rollback
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_price', 10, 2)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });
    }
};
