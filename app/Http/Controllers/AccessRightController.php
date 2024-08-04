<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccessRightResource;
use Exception;
use App\Models\AccessRight;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAccessRightRequest;
use App\Http\Requests\UpdateAccessRightRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AccessRightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            // recuperation des relations a charger
            $relations = $request->input('relations', []);

            $accessRights = AccessRight::with($relations)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'data' =>  AccessRightResource::collection($accessRights),
                'message' => 'Droits d\'accès récupérés avec succès.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des droits d\'accès. Veuillez réessayer plus tard.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccessRightRequest $request)
    {
        // Récupérer l'utilisateur authentifié
        $userId = Auth::id();

        // Obtenir les données validées
        $validatedData = $request->validated();
        $validatedData['user_id'] = $userId;

        try {
            // Créer une nouvelle instance d'AccessRight avec les données validées
            $accessRight = AccessRight::create($validatedData);
            $relations = $request->input('relations', []);
            $accessRight->load($relations);
            return response()->json([
                'message' => 'Droit d\'accès créé avec succès.',
                'data' => new AccessRightResource($accessRight)
            ], 201); // 201 Created
        } catch (Exception $e) {
            // Gestion des erreurs de création
            return response()->json([
                'message' => 'Erreur lors de la création du droit d\'accès. Veuillez réessayer plus tard.',
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
            $accessRight = AccessRight::findOrFail($id);
            return response()->json([
                'message' => 'Droit d\'accès récupéré avec succès.',
                'data' => $accessRight
            ], 200); // 200 OK
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Droit d\'accès non trouvé.',
                'error' => $e->getMessage()
            ], 404); // 404 Not Found
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération du droit d\'accès. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccessRightRequest $request, AccessRight $accessRight)
    {
        try {
            $accessRight->update($request->validated());

            return response()->json([
                'message' => 'Droit d\'accès mis à jour avec succès.',
                'data' => $accessRight
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Droit d\'accès non trouvé.',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du droit d\'accès. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessRight $accessRight)
    {
        try {
            $accessRight->delete();

            return response()->json([
                'message' => 'Droit d\'accès supprimé avec succès.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Droit d\'accès non trouvé.',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du droit d\'accès. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
