<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Offense;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function ask(Request $request)
    {
        try {
            $query = $request->query('query');
            $studentId = Auth::id();

            $offenses = Offense::with('penalties')->get();
            $offenseContext = $offenses->map(
                fn($o) =>
                "{$o->name}: " . $o->penalties->map(
                    fn($p) =>
                    "{$p->penalty_description}"
                )->join(', ')
            )->join("\n");

            $violations = Violation::with('offense')
                ->where('student_id', $studentId)
                ->where('status', '!=', 'void')
                ->get();

            $studentContext = $violations->isEmpty()
                ? "This student has a clean record with zero violations."
                : "This student has exactly " . $violations->count() . " violation(s) on record:\n" .
                $violations->map(
                    fn($v) =>
                    "- Offense: {$v->offense->name} | Count: 1st offense | Status: {$v->status}"
                )->join("\n");

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => 'llama-3.1-8b-instant', // free & fast
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => "You are the CTU-SAO (Student Affairs Office) Assistant. Answer ONLY based on the exact data provided below. Never speculate or reason ahead.

OFFENSES & PENALTIES:
{$offenseContext}

STUDENT RECORD (this is 100% accurate, do not change it):
{$studentContext}

STATUS DEFINITIONS:
- pending = the violation is under review and not yet resolved
- resolved = the violation has been resolved and penalty has been served
- void = the violation was dismissed and does not count

STRICT RULES:
- If a student asks about another student's violation or information, never share it and say you cannot provide another student's information
- If a student asks about their punishment or penalty, show the penalty for their CURRENT offense count only
- NEVER mention what the next offense would be unless the student explicitly asks 'what happens if I do it again'
- NEVER say 'this would be their 2nd offense' unless they actually have 2 violations on record
- If a violation status is 'resolved', tell the student it has already been resolved
- If a violation status is 'pending', tell the student it is still under review
- Base every answer strictly on the student record above, not on hypotheticals
- Keep responses short, max 3 sentences
- Never use bullet points, asterisks, or markdown — use plain conversational sentences only
- End every response with a warm closing like 'Let me know if you have other questions!' or 'Feel free to ask anything else.'
- Be concise and friendly",
                            ],
                            [
                                'role' => 'user',
                                'content' => $query,
                            ],
                        ],
                        'max_tokens' => 300,
                        'temperature' => 0.5,
                    ]);

            // DEBUG: show raw error if it fails
            if (!$response->successful()) {
                return response()->json([
                    'reply' => 'DEBUG: ' . $response->status() . ' — ' . $response->body()
                ]);
            }

            $reply = $response->json('choices.0.message.content')
                ?? 'Sorry, I could not process that.';

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Error: ' . $e->getMessage()]);
        }
    }
}