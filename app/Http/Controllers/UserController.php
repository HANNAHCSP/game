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
 * Update the specified resource in storage.
 */
public function update(Request $request, string $id)
{
    try {
        // Validate the incoming request
        $validatedData = $request->validate([
            'username' => 'sometimes|required|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6'
        ]);

        $user = User::findOrFail($id); // Find the user
        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']); // Encrypt the password
        }

        $user->update($validatedData); // Update the user data
        return response()->json($user); // Return updated user data
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $e->errors()
        ], 422); // 422 Unprocessable Entity
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to update user'], 500);
    }
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

    
public function show(string $id)
{
    try {
        $user = User::findOrFail($id); // Retrieve the user by ID
        return response()->json($user);
    } catch (\Exception $e) {
        return response()->json(['message' => 'User not found'], 404); // Return 404 if not found
    }
}

    /**
 * Remove the specified resource from storage.
 */
public function destroy(string $id)
{
    try {
        $user = User::findOrFail($id); // Find the user
        $user->delete(); // Delete the user
        return response()->json(['message' => 'User deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to delete user'], 500);
    }
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