<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\UserRole;
use Illuminate\Http\Request;
use function PHPSTORM_META\map;
use App\Http\Resources\UserRoleResource;

use App\Http\Requests\StoreUserRoleRequest;
use App\Http\Requests\UpdateUserRoleRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // recuperation des relations a charger
            $relations = request()->input('relations', []);

            $userRoles = UserRole::with($relations)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'message' => 'Rôles des utilisateurs récupérés avec succès.',
                'data' => UserRoleResource::collection($userRoles),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la récupération des rôles des utilisateurs.',
                'error' => $th->getMessage(),
            ], 500);
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
    public function store(StoreUserRoleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        try {
            $relations = request()->input('relations', []);

            // Récupération du UserRole avec les relations spécifiées
            $userRole = UserRole::with($relations)->findOrFail($id);

            return response()->json([
                'message' => 'Rôle utilisateur récupéré avec succès.',
                'data' => $userRole
            ], 200); // 200 OK
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Rôle utilisateur non trouvé.',
                'error' => $e->getMessage()
            ], 404); // 404 Not Found
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération du rôle utilisateur. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRoleRequest $request, UserRole $userRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRole $userRole)
    {
        //
    }

    public function attributeAccessRights(int $user_role, Request $request)
    {
        try {
            $userRoleModel = UserRole::findOrFail($user_role);
            $user = $request->user();
            $accessRights = $this->getAccessRightsFromRequest($request);
            $userRoleModel->accessRights()->syncWithPivotValues($accessRights, ['attribute_by' => $user->id]);

            return response()->json([
                'message' => 'Droits d\'accès attribués avec succès.'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Rôle utilisateur non trouvé.',
                'error' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'attribution des droits d\'accès. Veuillez réessayer plus tard.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getAccessRightsFromRequest(Request $request): array
    {
        $mainAccessRights = [];
        $subAccessRights = [];

        $accessRights = $request->input('accessRights', []);

        foreach ($accessRights as $accessRight) {
            $mainAccessRights[] = $accessRight['id'];

            if (!empty($accessRight['subAccessRights'])) {
                $subAccessRights = array_merge($subAccessRights, $accessRight['subAccessRights']);
            }
        }

        return array_merge($mainAccessRights, $subAccessRights);
    }
}
