<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "status" => $this->status,
            "point" => $this->status == "pending" ? $this->point_pending : $this->point_activity,
            "file_path" => $this->file_path,
            "file_url" => $this->file_url,
            "mission" => new MissionResource($this->mission),
        ];
    }
}
