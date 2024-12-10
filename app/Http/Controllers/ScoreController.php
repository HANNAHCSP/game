<?php

namespace App\Http\Controllers;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function highestScoreForUser(Request $request, $user_id)
    {
        $highestScore = Score::where('user_id', $user_id)
            ->orderBy('score', 'desc')
            ->first();
    
        if (!$highestScore) {
            return response()->json(['message' => 'No scores found for this user'], 404);
        }
    
        return response()->json($highestScore);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'username' => 'required',
            'score' => 'required|integer'
        ]);
    
        $score = Score::create($validatedData);
        return response()->json($score, 201); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
