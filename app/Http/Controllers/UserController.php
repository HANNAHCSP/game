<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();  // Retrieve all users from the database
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve users'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'username' => 'required|unique:users|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);
    
            // Create a new user with the validated data
            $user = User::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'credit_card' => [
                    'number' => '1234 5678 1234 5678',
                    'expiry' => '01/23',
                    'cvv' => '123'
                ]
            ]);
    
            // Return the created user as a JSON response
            return response()->json($user, 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation error details
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Error creating user: ' . $e->getMessage());
    
            // Return a generic error response
            return response()->json([
                'message' => 'Failed to create user. Please try again later.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // Validate the login request
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to log the user in
        $user = User::where('email', $validatedData['email'])->first();

        if ($user && \Hash::check($validatedData['password'], $user->password)) {
            // Authentication passed
            return response()->json([
                'message' => 'Login successful',
                'user' => $user
            ]);
        } else {
            // Authentication failed
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401); // 401 Unauthorized
        }
    }
}