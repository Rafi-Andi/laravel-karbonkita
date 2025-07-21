<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "email" => $this->email,
            "provinsi" => $this->provinsi,
            "kabupaten" => $this->kabupaten,
            "kecamatan" => $this->kecamatan,
            "kelurahan" => $this->kelurahan,
            "rw" => $this->rw,
            "rt" => $this->rt,
            "role" => $this->role,
            "point" => $this->point,
            "gender" => $this->gender,
            "profile_url" => $this->profile_url, 
        ];
    }
}
