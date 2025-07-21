<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Pest\Plugins\Only;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class AuthUserController extends Controller
{
    public function login(Request $request)
    {

        try {
            if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
                return response()->json([
                    "message" => "Username dan password salah",
                    "data" => null
                ], 400);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                "message" => "Berhasil login",
                "data" => [
                    "token" => $token,
                    "user" => new UserResource($user)
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Terjadi kesalahan",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function profile()
    {

        try {
            $user = Auth::user();

            return response()->json([
                'message' => 'berhasil mengambil profile',
                'data' => [
                    'user' => new UserResource($user)
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal mengambil profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::user();
            $user->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Berhasil logout',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'logout gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:5|max:40',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'rw' => 'required|numeric',
            'rt' => 'required|numeric',
            'gender' => 'required|string',
            'profile_url' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->provinsi = $data['provinsi'];
            $user->kabupaten = $data['kabupaten'];
            $user->kecamatan = $data['kecamatan'];
            $user->kelurahan = $data['kelurahan'];
            $user->rw = $data['rw'];
            $user->rt = $data['rt'];
            $user->gender = $data['gender'];
            $user->profile_url = $data['profile_url'];
            $user->save();
            
            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();
            return response()->json([
                'message' => 'Register berhasil',
                'data' => [
                    'token' => $token,
                    'user' => new UserResource($user)
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'logout gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
