<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     * POST /api/register
     * 
     * Body: {
     *   "name": "Juan Pérez",
     *   "email": "juan@example.com",
     *   "password": "password123",
     *   "password_confirmation": "password123",
     *   "dni": "12345678A"
     * }
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'dni' => ['nullable', 'string', 'max:20', 'unique:users,dni'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'dni' => $request->dni,
                'role_id' => 3, // Ajusta según tu base de datos (1=admin, 2=guia, 3=user)
                'status' => 'y',
            ]);

            event(new Registered($user));

            // Crear token
            $token = $user->createToken('api-token')->plainTextToken;

            // Cargar relación role
            $user->load('role');

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'dni' => $user->dni,
                    'role' => $user->role->name ?? 'user',
                    'status' => $user->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario',
                'error_details' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            ], 500);
        }
    }

    /**
     * Login de usuario
     * POST /api/login
     * 
     * Body: {
     *   "email": "juan@example.com",
     *   "password": "password123"
     * }
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $user = User::where('email', $request->email)
                        ->where('status', 'y')
                        ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Las credenciales proporcionadas son incorrectas.'],
                ]);
            }

            // Opcional: Revocar tokens anteriores
            // $user->tokens()->delete();

            // Crear token
            $token = $user->createToken('api-token')->plainTextToken;

            // Cargar relación role
            $user->load('role');

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'dni' => $user->dni,
                    'role' => $user->role->name ?? 'user',
                    'status' => $user->status,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar sesión',
                'error_details' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            ], 500);
        }
    }

    /**
     * Logout de usuario
     * POST /api/logout
     * Header: Authorization: Bearer {token}
     */
    public function logout(Request $request)
    {
        try {
            // Revocar el token actual
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error_details' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            ], 500);
        }
    }
}
