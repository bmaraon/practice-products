<?php

namespace App\Http\Resources;

use App\Models\User;
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
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'age'        => $this->age,
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y') : null,
            'created_by' => $this->getUserDetails($this->created_by),
            'updated_by' => $this->getUserDetails($this->updated_by),
        ];
    }

    /**
     * Get user details
     *
     * @param int $userId
     * @return array
     *
     */
    private function getUserDetails($userId)
    {
        $user = User::where('id', $userId)->select(['id', 'name'])->first();
        return $user ? $user->toArray() : null;
    }
}
