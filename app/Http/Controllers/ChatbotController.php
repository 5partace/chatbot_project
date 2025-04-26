<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    /*
        API endpoint to facilitate user's conversation with gemini chatbot
        Also responsible for storing the user's conversation in laravel backend session
    */
    public function chat(Request $request)
    {
        $message = $request->input('message');

        // Automatically initializes or retrieves chat history tied to the current browser session without needing to manually pass a session token
        if (!session()->has('chat_history')) {
            session(['chat_history' => []]);
        }

        // Retrieve current chat history
        $history = session('chat_history');

        // Add user's message to the history
        $history[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        // Call Gemini API with full history
        $reply = $this->callGeminiAPI($history);

        // Add model's reply to history
        $history[] = [
            'role' => 'model',
            'parts' => [['text' => $reply]]
        ];

        // Update the session with the new history
        session(['chat_history' => $history]);

        return response()->json([
            'reply' => $reply
        ]);
    }

    /*
        Function to assist with calling gemini's API endpoint
    */
    private function callGeminiAPI($chatHistory)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;
        $client = new Client();
        try {
            $response = $client->post($url, [
                'json' => [
                    'contents' => $chatHistory,
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            // error_log(json_encode($data, JSON_PRETTY_PRINT));
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, something went wrong. Please try again later.';
        } catch (\Exception $e) {
            // error_log("Gemini API Error: " . $e->getMessage());
            return 'Sorry, something went wrong. Please try again later.'; // Return generic message to user
        }
    }
}
