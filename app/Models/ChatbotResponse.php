<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'is_active',
        'created_by',
        'product_ids',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'product_ids' => 'array',
    ];

    // Relationship với User (người tạo)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get all active responses
    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }

    // Tìm câu trả lời tương ứng với câu hỏi
    public static function getAutoReply($userMessage)
    {
        $userMessage = strtolower(trim($userMessage));
        
        // Tìm kiếm chính xác hoặc gần đúng
        $response = self::where('is_active', true)
            ->whereRaw('LOWER(question) LIKE ?', ["%{$userMessage}%"])
            ->first();

        if ($response) {
            return $response;
        }

        // Tìm kiếm từ khóa - kiểm tra nếu câu hỏi chứa các từ khóa trong question
        $responses = self::where('is_active', true)->get();
        
        foreach ($responses as $resp) {
            $keywords = array_filter(str_word_count(strtolower($resp->question), 1));
            foreach ($keywords as $keyword) {
                if (strlen($keyword) > 2 && strpos($userMessage, $keyword) !== false) {
                    return $resp;
                }
            }
        }

        return null;
    }
}
