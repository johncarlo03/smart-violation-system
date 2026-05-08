<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Offense;
use App\Models\Violation;
use App\Models\OffensePenalty;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        try {
            $query = $request->query('query');
            $studentId = Auth::id();

            $offense = Offense::where('name', 'LIKE', "%{$query}%")->first();

            if (!$offense) {
                return response()->json(['reply' => "I couldn't find information on that. Try 'Smoking' or 'ID Policy'."]);
            }

            $strikeCount = Violation::where('student_id', $studentId)
                ->where('offense_id', $offense->id)
                ->where('status', '!=', 'void')
                ->count();

            $nextStrike = $strikeCount + 1;

            $penalty = OffensePenalty::where('offense_id', $offense->id)
                ->where('level', $nextStrike)
                ->first();

            $reply = "Regarding {$offense->name}: You have <b>{$strikeCount}</b> recorded instances. ";
            $reply .= $penalty 
                ? "The penalty for Strike #{$nextStrike} is: **{$penalty->penalty_description}**."
                : "Please visit the SAO office for further guidance on this matter.";

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['reply' => "System Error: " . $e->getMessage()], 500);
        }
    }
    // public function ask(Request $request)
    // {
    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
    //         'Content-Type' => 'application/json',
    //         'HTTP-Referer' => url('/'),
    //         'X-Title' => 'Student Assistant Chatbot',
    //     ])->post('https://openrouter.ai/api/v1/chat/completions', [
    //                 'model' => 'google/gemma-4-31b-it:free',
    //                 'messages' => [
    //                     [
    //                         'role' => 'system',
    //                         'content' => 'You are a helpful school assistant chatbot that answers student questions about violations, rules, and campus concerns.'
    //                     ],
    //                     [
    //                         'role' => 'user',
    //                         'content' => $request->message
    //                     ]
    //                 ]
    //             ]);

    //     $data = $response->json();

    //     return response()->json([
    //         'reply' => $data['choices'][0]['message']['content']
    //             ?? ($data['error']['message'] ?? 'No response from AI'),
    //     ]);
    // }
}