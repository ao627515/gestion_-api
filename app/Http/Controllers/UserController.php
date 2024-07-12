<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

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
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'message' => 'Utilisateur récupéré avec succès.',
                'data' => new UserResource($user)
            ], 200); // 200 OK
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Utilisateur non trouvé.',
                'error' => $e->getMessage()
            ], 404); // 404 Not Found
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération de l\'utilisateur. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
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
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());
            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès.',
                'data' => new UserResource($user)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Utilisateur non trouvé.',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Trouver l'utilisateur ou lever une exception
            $user = User::findOrFail($id);

            // Supprimer l'utilisateur
            $user->delete();

            return response()->json([
                'message' => 'Utilisateur supprimé avec succès.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Utilisateur non trouvé.',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de l\'utilisateur. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
