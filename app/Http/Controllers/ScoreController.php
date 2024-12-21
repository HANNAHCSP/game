<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\User; 
use Illuminate\Http\Request;

class ScoreController extends Controller
{
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
    
        // Add the new score to the scores table
        $newScore = Score::create([
            'user_id' => $user_id,
            'username' => $user->username,
            'score' => $validatedData['score']
        ]);
    
        // Compare the new score with the current high score and update if higher
        if ($user->score < $validatedData['score']) {
            $user->update(['score' => $validatedData['score']]); // Update the user's high score
            $message = "New high score achieved and updated!";
        } else {
            $message = "Game completed, but high score remains unchanged.";
        }
    
        return response()->json([
            'message' => $message,
            'newScore' => $newScore,
            'user' => $user // Include the user's current data
        ]);
    }
}    