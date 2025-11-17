<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'brand_id',
        'subcategory_id',
        'price',
        'unit',
        'status',
        'description',
        'user_id',
    ];


}
