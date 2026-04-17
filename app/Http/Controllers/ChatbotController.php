<?php

namespace App\Http\Controllers;

use App\Models\ChatbotMessage;
use App\Models\ChatbotResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    // Gửi tin nhắn từ khách hàng
    public function sendMessage(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng chat.',
            ], 401);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
            'visitor_name' => 'nullable|string|max:255',
            'visitor_email' => 'nullable|email|max:255',
        ]);

        $user = auth()->user();

        // Tìm kiếm câu trả lời từ chatbot responses
        $response = ChatbotResponse::getAutoReply($validated['message']);

        $isAutoReply = false;
        $replyMessage = null;
        $status = 'pending'; // Mặc định là chờ admin

        if ($response) {
            $replyMessage = $response->answer;
            $isAutoReply = true;
            $status = 'answered';
        }

        $message = ChatbotMessage::create([
            'user_id' => $user->id,
            'visitor_name' => $validated['visitor_name'] ?? $user->name,
            'visitor_email' => $validated['visitor_email'] ?? $user->email,
            'message' => $validated['message'],
            'response' => $replyMessage,
            'status' => $status,
            'is_auto_reply' => $isAutoReply,
        ]);

        return response()->json([
            'success' => true,
            'message' => $isAutoReply ? '✅ Chatbot trả lời tự động' : '⏳ Tin nhắn đã gửi cho admin, vui lòng chờ',
            'is_auto_reply' => $isAutoReply,
            'response' => $replyMessage,
            'chat' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'visitor_name' => $message->visitor_name,
                'visitor_email' => $message->visitor_email,
                'message' => $message->message,
                'response' => $message->response,
                'status' => $message->status,
                'is_auto_reply' => $message->is_auto_reply,
                'created_at' => optional($message->created_at)->toDateTimeString(),
                'updated_at' => optional($message->updated_at)->toDateTimeString(),
            ],
        ]);
    }

    // Lấy lịch sử chat (cho khách hàng)
    public function getChatHistory()
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem lịch sử chat.',
                'messages' => [],
            ], 401);
        }

        $messages = ChatbotMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'visitor_name' => $message->visitor_name,
                    'visitor_email' => $message->visitor_email,
                    'message' => $message->message,
                    'response' => $message->response,
                    'status' => $message->status,
                    'is_auto_reply' => $message->is_auto_reply,
                    'created_at' => optional($message->created_at)->toDateTimeString(),
                    'updated_at' => optional($message->updated_at)->toDateTimeString(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}