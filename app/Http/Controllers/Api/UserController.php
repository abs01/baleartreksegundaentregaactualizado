<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios (solo admin)
     * GET /api/user
     */
    public function index()
    {
        // SELECCIÓ DE LES DADES
        $users = User::with(["role"])
                    ->where('status', 'y')
                    ->orderBy('role_id')
                    ->orderBy('id')
                    ->get();
        
        return UserResource::collection($users)->additional(['meta' => 'Usuaris mostrats correctament']);
    }

    /**
     * Mostrar un usuario específico
     * GET /api/user/{user}
     */
    public function show(User $user)
    {   
        // Verificar que l'usuari estigui actiu
        if ($user->status == 'y') {
            // SELECCIÓ DE LES DADES
            $user->load(['meeting',
                         'meetings',
                         'comments',
                         'comments.images']);
            
            // SELECCIÓ DEL FORMAT DE LA RESPOSTA
            return (new UserResource($user))->additional(['meta' => 'Usuari mostrat correctament']);
        } else {
            return response()->json(['message' => 'Usuari no disponible'], 404);
        }
    }
 
    /**
     * Actualizar un usuario
     * PUT/PATCH /api/user/{user}
     */
    public function update(UserRequest $request, User $user)
    {
        if ($user->status !== 'y') {
            return response()->json(['message' => 'Usuari no disponible'], 404);
        }
        
        $user->update($request->all());
        return (new UserResource($user))->additional(['meta' => 'Usuari modificat correctament']);
    }
    
    /**
     * Eliminar (desactivar) un usuario
     * DELETE /api/user/{user}
     */
    public function destroy(User $user)
    {
        $user_role = Role::where('id', $user->role_id)->value('name');
        
        try {
            $user->status = 'n';
            
            if (in_array($user_role, ["admin", "guia"])) {
                throw (new Exception('Usuari restringit de tipus ' . $user_role));
            }
            
            // Delete comment images
            foreach ($user->comments as $comment) {
                $comment->status = 'n';
                $comment->save();
            }
            
            // Detach permite la desconexión de la tabla meeting_users
            $user->meetings()->detach();
            $user->save();
            
            return (new UserResource($user))->additional(['meta' => 'Usuari eliminat correctament']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'S\'ha produït un error al tractar les dades',
                'error_details' => $e->getMessage(),
            ], 400);  // ⬅️ Cambiado de 200 a 400 (error de cliente)
        }
    }
}