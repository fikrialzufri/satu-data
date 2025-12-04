<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 */
class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('cors');
    }

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Auth"},
     *     operationId="login",
     *     summary="Login",
     *     description="Login untuk mendapatkan token JWT menggunakan email atau username",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", description="Username atau email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Login berhasil",
     *                 "data": {
     *                     "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
     *                     "user": {
     *                         "id": "1",
     *                         "name": "John Doe",
     *                         "email": "user@example.com",
     *                         "username": "johndoe",
     *                         "nik": "123456"
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Login gagal",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Email/username atau password salah"
     *             }
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['username', 'password']);

            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email/username atau password salah'
                ], 401);
            }

            $user = auth()->user();

            // Hapus token lama jika ada untuk menghindari konflik
            $user->tokens()->delete();

            // get token dengan retry mechanism
            $maxRetries = 3;
            $token = null;

            for ($i = 0; $i < $maxRetries; $i++) {
                try {
                    $token = $user->createToken('auth_token')->plainTextToken;
                    break;
                } catch (\Exception $e) {
                    if ($i === $maxRetries - 1) {
                        throw $e;
                    }
                    // Tunggu sebentar sebelum retry
                    usleep(100000); // 100ms
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'username' => $user->username,
                        'token' => $token
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     operationId="logout",
     *     summary="Logout",
     *     description="Logout dan menghapus token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="Logout berhasil",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Logout berhasil"
     *             }
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/auth/user",
     *     tags={"Auth"},
     *     operationId="getUser",
     *     summary="Get User",
     *     description="Mendapatkan data user yang sedang login",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response="200",
     *         description="Berhasil mendapatkan data user",
     *         @OA\JsonContent(
     *             example={
     *                 "success": true,
     *                 "message": "Berhasil mendapatkan data user",
     *                 "data": {
     *                     "id": "1",
     *                     "name": "John Doe",
     *                     "email": "user@example.com",
     *                     "nik": "123456"
     *                 }
     *             }
     *         )
     *     )
     * )
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Token tidak valid atau sudah kadaluarsa'
                ], 401);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendapatkan data user',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'nik' => $user->nik
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
