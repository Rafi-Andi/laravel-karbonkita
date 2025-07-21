<?php

namespace App\Http\Controllers;

use App\Http\Resources\MissionResource;
use Exception;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();

            if ($user->role == 'user') {
                return response()->json([
                    "message" => "Anda dilarang akses"
                ], 403);
            }

            $mission = Mission::all();

            if (!$mission) {
                return response()->json([
                    "message" => "Data misi kosong"
                ], 404);
            }

            return response()->json([
                "message" => "Berhasil menampilkan misi",
                "data" => MissionResource::collection($mission)
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal menampilkan misi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "category_id" => "required|numeric",
            "title" => "required|string|min:4|max:30",
            "description" => "required|string|min:4",
            "point" => "required|numeric",
        ]);
        if (auth()->user()->role == 'user') {
            return response()->json([
                "message" => "Anda dilarang akses"
            ], 403);
        }

        DB::beginTransaction();
        try {
            $mission = new Mission();
            $mission->category_id = $data['category_id'];
            $mission->title = $data['title'];
            $mission->description = $data['description'];
            $mission->point = $data['point'];
            $mission->save();

            DB::commit();

            return response()->json([
                "message" => "berhasil menambahkan misi",
                "data" => $mission
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal menambahkan misi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = auth()->user();

            if ($user->role == 'user') {
                return response()->json([
                    "message" => "Anda dilarang akses"
                ], 403);
            }

            Mission::destroy($id);

            return response()->json([
                "message" => "Berhasil menghapus misi",
                "data" => null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal menambahkan misi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
