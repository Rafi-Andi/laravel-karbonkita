<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class KarbonController extends Controller
{
    public function rank(Request $request)
    {
        try {
            $user = auth()->user();

            $query = User::query();

            if ($request->wilayah == 'kelurahan') {
                $query->where('kelurahan', $user->kelurahan)->orderBy('point', 'desc')->get();
            }

            if ($request->wilayah == 'rw') {
                $query->where('kelurahan', $user->kelurahan)->where('rw', $user->rw)->orderBy('point', 'desc')->get();
            }

            if ($request->wilayah == 'rt') {
                $query->where('kelurahan', $user->kelurahan)->where('rw', $user->rw)->where('rt', $user->rt)->orderBy('point', 'desc')->get();
            }

            if (!$request->wilayah) {
                return response()->json([
                    "message" => "query harus menggunakan wilayah"
                ]);
            }

            if ($request->wilayah != 'rt' && $request->wilayah != 'rw' && $request->wilayah != 'kelurahan') {
                return response()->json([
                    "message" => "query wilayah harus rt rw kelurahan"
                ]);
            }

            $rank = $query->get();

            return response()->json([
                "message" => "berhasil mengambil peringkat pengguna",
                "data" => UserResource::collection($rank)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil peringkat pengguna',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
