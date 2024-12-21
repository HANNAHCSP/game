<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\User; 
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // ...existing code...

    public function highestScoreForUser(Request $request, $user_id)
    {
        // Get the highest score for the user
        $highestScore = Score::where('user_id', $user_id)
            ->orderBy('score', 'desc')
            ->first();

        if (!$highestScore) {
            return response()->json(['message' => 'No scores found for this user'], 404);
        }

        // Find the user and update their score if necessary
        $user = User::findOrFail($user_id); // Ensures user exists

        if ($user->score < $highestScore->score) {
            $user->score = $highestScore->score;
            $user->save();
        }

        return response()->json([
            'message' => 'Highest score retrieved and user high score updated successfully.',
            'highestScore' => $highestScore,
            'user' => $user
        ]);
    }

    public function completeGame(Request $request, $user_id)
    {
        // Validate the score input
        $validatedData = $request->validate([
            'score' => 'required|integer'
        ]);
    
        // Ensure the user exists
        $user = User::findOrFail($user_id);

        // Store the new score
        $score = new Score();
        $score->user_id = $user_id;
        $score->username = $user->username;
        $score->score = $validatedData['score'];
        $score->save();

        return response()->json([
            'message' => 'Score saved successfully.',
            'score' => $score
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'score' => 'required|integer'
        ]);

        // Create a new score
        $score = Score::create([
            'user_id' => $validatedData['user_id'],
            'score' => $validatedData['score'],
            'username' => User::find($validatedData['user_id'])->username
        ]);

        return response()->json([
            'message' => 'Score stored successfully.',
            'score' => $score
        ]);
    }

    public function topThreeScores()
    {
        // Retrieve the top 3 highest scores
        $topScores = Score::orderBy('score', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'message' => 'Top 3 highest scores retrieved successfully.',
            'topScores' => $topScores
        ]);
    }

    public function gamesPlayed($user_id)
    {
        // Count the number of games played by the user
        $gamesPlayed = Score::where('user_id', $user_id)->count();

        return response()->json([
            'message' => 'Number of games played retrieved successfully.',
            'gamesPlayed' => $gamesPlayed
        ]);
    }

}