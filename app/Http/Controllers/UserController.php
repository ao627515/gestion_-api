<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
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
        try {
            $users = User::orderBy('created_at', 'desc')->get();
            return response()->json([
                'data' => UserResource::collection($users),
                'message' => 'Users retrieved successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur. Veuillez réessayer plus tard.',
                'error' => $th->getMessage()
            ]);
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
    public function store(StoreUserRequest $request)
    {
        // Valider les données de la demande
        $validated = $request->validated();

        // Générer un mot de passe si non fourni
        $validated['password'] = isset($validated['password']) ? Hash::make($validated['password']) : Hash::make('password');

        // Générer un identifiant unique numérique
        do {
            $validated['registration_number'] = now()->year . random_int(1000, 9999);
        } while (User::where('registration_number', $validated['registration_number'])->exists());

        // Créer l'utilisateur avec les données validées
        try {
            $user = User::create($validated);

            return response()->json([
                'message' => 'Utilisateur créé avec succès.',
                'data' => new UserResource($user)
            ], 201); // 201 Created
        } catch (\Exception $e) {
            // Gestion des erreurs de création
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
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
        return response()->json([
            'message' => 'Utilisateur trouve.',
            'data' => new UserResource($user)
        ], 200);
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
