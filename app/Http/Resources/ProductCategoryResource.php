<?php

namespace App\Http\Resources;

use App\Models\productCategoryAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!isset($this->id) || is_null($this->id)) {
            return [];
        }

        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'description'          => $this->description,
            'user_age_restriction' => $this->getUserAgeRange($this->id),
            'created_at'           => $this->created_at ? $this->created_at->format('d/m/Y') : null,
            'updated_at'           => $this->updated_at ? $this->updated_at->format('d/m/Y') : null,
            'created_by'           => $this->getUserDetails($this->created_by),
            'updated_by'           => $this->getUserDetails($this->updated_by),
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

    /**
     * Get user age range
     *
     * @param int $categoryId
     * @return array
     *
     */
    private function getUserAgeRange($categoryId)
    {
        $productCategoryAccess = ProductCategoryAccess::where('product_category_id', $categoryId)->select(['min_user_age', 'max_user_age'])->first();
        return $productCategoryAccess ? $productCategoryAccess->toArray() : null;
    }
}
