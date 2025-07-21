<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Mission;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ActivityResource;

class ActivityController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        try {
            $activity = Activity::where('user_id', $user->id)->get();

            return response()->json([
                "message" => "Berhasil mendapatkan histori",
                "data" => ActivityResource::collection($activity)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal menampilkan histori aktifitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            "mission_id" => "numeric|required",
            "file_path" => "required|image|mimes:png,jpg,jpeg|max:2000",
            "description" => "string|required",
        ]);

        $path = $request->file('file_path')->store('img', 'public');

        $url = Storage::url($path);

        $mission = Mission::find($data['mission_id']);
        $user = auth()->user();

        if (!$mission) {
            return response()->json([
                "message" => "misi id tidak ada"
            ], 404);
        }

        DB::beginTransaction();

        try {
            $activity = new Activity();
            $activity->user_id = $user->id;
            $activity->mission_id = $data['mission_id'];
            $activity->file_path = $data['file_path'];
            $activity->description = $data['description'];
            $activity->point_pending = $mission->point;
            $activity->file_path = $path;
            $activity->file_url = $url;
            $activity->save();

            DB::commit();

            return response()->json([
                "message" => "berhasil menambahkan aktifitas",
                "data" => [
                    "user" => new UserResource($user),
                    "activity" => new ActivityResource($activity)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal menambahkan aktifitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verification(Request $request, $id)
    {
        $data = $request->validate([
            "status" => "required|string|in:accept,rejected",
            "admin_notes" => "required|string"
        ]);

        try {
            $user = auth()->user();
            $activity = Activity::find($id);

            if (!$activity) {
                return response()->json([
                    "message" => "misi id tidak ada"
                ], 404);
            }

            $point_pending = $activity->point_pending;


            if ($user->role == 'user') {
                return response()->json([
                    "message" => "Anda dilarang akses"
                ], 403);
            }

            $path = $activity->file_path;


            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                echo "Foto berhasil dihapus.";
            } else {
                echo "Foto tidak ditemukan.";
            }

            $activity->update([
                "status" => $data['status'],
                "admin_notes" => $data['admin_notes'],
                "point_activity" => $data['status'] == 'accept' ? $point_pending : 0,
                "point_pending" => 0
            ]);

            $activity->refresh();

            return response()->json([
                "message" => "berhasil verifikasi aktifitas",
                "data" => [
                    "activity" => $activity,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal verifikasi aktifitas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPending()
    {
        try {

            $user = auth()->user();
            if ($user->role == 'user') {
                return response()->json([
                    "message" => "Anda dilarang akses"
                ], 403);
            }

            $activityPending = Activity::where('status', 'pending')->get();

            if ($activityPending->isEmpty() ) {
                return response()->json([
                    'message' => 'Tidak ada aktifitas pending',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'message' => 'Berhasil mendapatkan aktifitas pending',
                'data' => ActivityResource::collection($activityPending)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'gagal mendapatkan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
