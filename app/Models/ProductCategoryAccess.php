<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryAccess extends Model
{
    use HasFactory;

    /**
     * @var string
     *
     */
    public $table = 'product_category_access';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_category_id',
        'min_user_age',
        'max_user_age',
    ];
}
