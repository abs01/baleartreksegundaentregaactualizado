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
    public function index(Request $request)
    {
        $query = User::with('role');

        // Search by name, lastname, email, DNI, or role name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('lastname', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('dni', 'like', '%' . $search . '%')
                  ->orWhereHas('role', function($roleQuery) use ($search) {
                      $roleQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // By default, show only active users
            $query->where('status', 'y');
        }

        // Order and paginate
        $users = $query->orderBy('role_id')->orderBy('name')->paginate(10);

        // Get all roles for the filter dropdown
        $roles = Role::orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'dni' => 'nullable|string|max:20|unique:users,dni',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
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
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userCRUD->id,
            'dni' => 'nullable|string|max:20|unique:users,dni,' . $userCRUD->id,
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
        $user_role = Role::where('id', $userCRUD->role_id)->value('name');
        
        try {
            if (in_array($user_role, ["admin", "guia"])) {
                throw new Exception('Usuario restringido de tipo ' . $user_role);
            }

            // Soft delete: change status to 'n'
            $userCRUD->status = 'n';

            // Delete comment images
            foreach ($userCRUD->comments as $comment) {
                $comment->status = 'n';
                $comment->save();
            }

            // Detach meetings
            $userCRUD->meetings()->detach();
            $userCRUD->save();

            return redirect()->route('userCRUD.index')->with('success', 'User deleted successfully');
        } catch (Exception $e) {
            return redirect()->route('userCRUD.index')->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}