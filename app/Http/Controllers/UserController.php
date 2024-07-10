<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all(), 200);
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
        $validated = $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'role' => 'required|in:caissier,gerant,admin',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'nullable|string',
        ]);

        // Si le mot de passe n'est pas fourni, vous pouvez en générer un par défaut.
        if (empty($validated['password'])) {
            $validated['password'] = Hash::make('password');
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user = User::create($validated);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }
        return response()->json($user, 200);
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
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'role' => 'required|in:caissier,gerant,admin',
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'password' => 'required|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès'
        ], 200);
    }
}
