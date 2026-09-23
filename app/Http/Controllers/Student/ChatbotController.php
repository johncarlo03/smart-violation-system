<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\OffensePenalty;
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

        // 1. Fetch Offenses with Penalties
        $offenses = Offense::with('penalties')->get();
        $offenseContext = $offenses->map(function ($o) {
            if ($o->penalties->isEmpty()) {
                return "{$o->name}: No penalties defined.";
            }

            $penaltiesList = $o->penalties->map(function ($p) {
                $level = $p->level ?? '1st';
                $desc = $p->penalty_description ?? 'No description';
                return "{$level} Offense = {$desc}";
            })->join('; ');

            return "{$o->name} penalties: {$penaltiesList}";
        })->join("\n");

        // 2. Fetch Active Student Violations
        $violations = Violation::with('offense')
            ->where('student_id', $studentId)
            ->where('status', '!=', 'void')
            ->get();

        $offenseCounts = [];
        $studentContext = $violations->isEmpty()
            ? "This student has a clean record with zero violations."
            : "This student has the following record:\n" .
            $violations->map(function ($v) use (&$offenseCounts) {
                $offenseId = $v->offense_id;
                $offenseCounts[$offenseId] = ($offenseCounts[$offenseId] ?? 0) + 1;
                $currentLevel = $offenseCounts[$offenseId];

                return "- Offense: {$v->offense?->name} (Level {$currentLevel} for this type) | Status: {$v->status}";
            })->join("\n");

        // 3. API Request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.groq.key', env('GROQ_API_KEY')),
            'Content-Type' => 'application/json',
        ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'openai/gpt-oss-20b',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are the CTU-SAO (Student Affairs Office) Assistant. Answer ONLY based on the exact data provided below. Never speculate or reason ahead.

OFFENSES & LEVEL PENALTIES:
{$offenseContext}

STUDENT RECORD:
{$studentContext}

STATUS DEFINITIONS:
- Pending = the violation is under review and/or the required penalty has not yet been served
- Resolved = the violation penalty has been served and completed
- Dismissed = the violation was dismissed and does not count

STRICT RULES:
- When mentioning a violation status (Pending, Resolved, Dismissed), ALWAYS write it in **bold** (e.g. **Pending**, **Resolved**, **Dismissed**).
- When a student asks what penalties an offense has, list the penalties clearly in sentence form (e.g., 'For [Offense Name], the 1st offense is..., the 2nd offense is...').
- To tell a student how to resolve/fix an offense, check the penalty description matching their CURRENT offense level for that specific offense.
- If a violation status is 'resolved', inform them that it is already **Resolved** and no further action is needed.
- If a violation status is 'pending', tell them it is currently **Pending** and explain what penalty action they must fulfill to clear it.
- Never use markdown bullet lists, asterisks lists, or headers — present all information in clean, conversational sentences, using double asterisks ONLY for bolding status terms (**Status**).
- End every response with a warm closing like 'Let me know if you have other questions!' or 'Feel free to ask anything else.'
- Be concise, clear, and friendly.
- If the question is out of your knowledge or expertise, direct the student to visit the SAO office for further clarification.",
                ],
                [
                    'role' => 'user',
                    'content' => $query,
                ],
            ],
            'max_tokens' => 600,
            'temperature' => 0.2,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'reply' => 'DEBUG: ' . $response->status() . ' — ' . $response->body()
            ]);
        }

        $reply = $response->json('choices.0.message.content')
            ?? 'Sorry, I could not process that query right now.';

        return response()->json(['reply' => trim($reply)]);

    } catch (\Exception $e) {
        return response()->json(['reply' => 'Error: ' . $e->getMessage()]);
    }
}
}