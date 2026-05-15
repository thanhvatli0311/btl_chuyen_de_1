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
        Schema::table('chatbot_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('chatbot_messages', 'related_products')) {
                $table->json('related_products')->nullable()->after('is_auto_reply');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chatbot_messages', 'related_products')) {
                $table->dropColumn('related_products');
            }
        });
    }
};
