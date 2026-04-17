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
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('visitor_name')->nullable(); // Tên khách vãng lai nếu chưa login
            $table->string('visitor_email')->nullable(); // Email khách hàng
            $table->text('message'); // Tin nhắn từ khách hàng
            $table->text('response')->nullable(); // Phản hồi từ chatbot hoặc admin
            $table->enum('status', ['pending', 'answered', 'archived'])->default('pending'); // pending = chờ admin, answered = đã trả lời
            $table->boolean('is_auto_reply')->default(false); // true = trả lời tự động, false = admin trả lời
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_messages');
    }
};
