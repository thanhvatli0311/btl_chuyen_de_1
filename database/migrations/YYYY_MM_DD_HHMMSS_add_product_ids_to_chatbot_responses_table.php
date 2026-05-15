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
        Schema::table('chatbot_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('chatbot_responses', 'product_ids')) {
                $table->json('product_ids')->nullable()->after('answer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_responses', function (Blueprint $table) {
            $table->dropColumn('product_ids');
        });
    }
};