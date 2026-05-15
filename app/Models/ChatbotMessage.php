<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    use HasFactory;

    protected $table = 'chatbot_messages';

    protected $fillable = [
        'user_id',
        'visitor_name',
        'visitor_email',
        'message',
        'response',
        'status',
        'is_auto_reply',
        'related_products',
    ];

    protected $casts = [
        'is_auto_reply' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'related_products' => 'array',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kiểm tra xem tin nhắn đã được trả lời hay chưa
    public function isAnswered()
    {
        return $this->status === 'answered' && !is_null($this->response);
    }

    // Lấy tất cả tin nhắn chưa trả lời
    public static function pending()
    {
        return self::where('status', 'pending')->orderBy('created_at', 'desc');
    }

    public function scopeConversationForMessage($query, self $message)
    {
        return $query->when($message->user_id, function ($conversationQuery) use ($message) {
            $conversationQuery->where('user_id', $message->user_id);
        }, function ($conversationQuery) use ($message) {
            $conversationQuery->where('visitor_email', $message->visitor_email);
        })->orderBy('created_at', 'asc');
    }
}