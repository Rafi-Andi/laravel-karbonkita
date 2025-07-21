<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        if ($user->role == 'user') {
            return response()->json([
                "message" => "Anda dilarang akses"
            ], 403);
        }

        try {
            $category = Category::all();

            return response()->json([
                "message" => "berhasil mendapatkan seluruh kategori",
                "data" => CategoryResource::collection($category)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal mendapatkan seluruh kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            "content" => "required|string|min:4",
            "color" => "required|string",
        ]);

        $user = auth()->user();

        if ($user->role == 'user') {
            return response()->json([
                "message" => "Anda dilarang akses"
            ], 403);
        }

        DB::beginTransaction();

        try {
            $category = new Category();
            $category->content = $data['content'];
            $category->color = $data['color'];
            $category->save();

            DB::commit();

            return response()->json([
                "message" => "Berhasil menambahkan kategori",
                "data" => $category
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal menambahkan kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = auth()->user();

            if ($user->role == 'user') {
                return response()->json([
                    "message" => "Anda dilarang akses"
                ], 403);
            }

            Category::destroy($id);

            return response()->json([
                "message" => "Berhasil menghapus kategori",
                "data" => null
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
