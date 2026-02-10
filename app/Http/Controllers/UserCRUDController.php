<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->orderBy('role_id')->paginate(3);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('userCRUD.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $userCRUD)
    {
        return view('users.show', compact('userCRUD'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $userCRUD)
    {
        $roles = Role::all();
        return view('users.edit', compact('userCRUD', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $userCRUD)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userCRUD->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $userCRUD->update($validated);

        return redirect()->route('userCRUD.show', $userCRUD)->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $userCRUD)
    {
        // Detach meetings to remove foreign key constraints
      $user_role = Role::where('id', $userCRUD->role_id)->value('name');

        try {
            $userCRUD->status = 'n';

            if (in_array($user_role, ["admin", "guia"])) {
                throw (new Exception('Usuari restringit de tipus ' . $user_role));
            }
            // Delete comment images
            foreach ($userCRUD->comments as $comment) {
                $comment->status = 'n';
                $comment->save();
            }
            //detach permite la desconexión de la tabla meeting_users
            //https://laravel.com/docs/12.x/eloquent-relationships
            $userCRUD->meetings()->detach();

            $userCRUD->save();
            // $userCRUD->delete();

        return redirect()->route('userCRUD.index')->with('success', 'User deleted successfully');
        } catch (Exception $e) {
            // GESTIÓ DE L'ERROR
            // Retorna un JSON amb un missatge d'error i un codi d'estat 500
                return redirect()->route('userCRUD.index')->with('error', 'Error al eliminar l\'usuari: ' . $e->getMessage());

        }
    }

    
}
