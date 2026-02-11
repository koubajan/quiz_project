<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function start()
    {
        // Get 5 random questions with their answers
        // We hide 'is_correct' from the frontend
        $questions = Question::with([
            'answers' => function ($query) {
                $query->select('id', 'question_id', 'text');
            }
        ])->inRandomOrder()->limit(5)->get();

        return response()->json($questions);
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.answer_id' => 'required|exists:answers,id',
        ]);

        $score = 0;
        $total = count($validated['answers']);
        $results = [];

        foreach ($validated['answers'] as $submission) {
            $question = Question::find($submission['question_id']);
            $selectedAnswer = Answer::find($submission['answer_id']);

            $isCorrect = $selectedAnswer->is_correct;

            if ($isCorrect) {
                $score++;
            }

            // Find the correct answer text for feedback
            $correctAnswer = $question->answers()->where('is_correct', true)->first();

            $results[] = [
                'question_id' => $question->id,
                'is_correct' => $isCorrect,
                'correct_answer_id' => $correctAnswer->id,
                'correct_answer_text' => $correctAnswer->text,
                'explanation' => "Správná odpověď je: " . $correctAnswer->text // Simple explanation
            ];
        }

        return response()->json([
            'score' => $score,
            'total' => $total,
            'percentage' => ($total > 0) ? round(($score / $total) * 100) : 0,
            'results' => $results
        ]);
    }
}
