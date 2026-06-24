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
        'product_code',
        'brand_id',
        'subcategory_id',
        'price',
        'unit',
        'status',
        'description',
        'user_id',
        'barcode',
        'cost_price',
        'weight',
        'dimensions',
        'supplier_id',
        'minimum_stock_level',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'subcategory_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'product_id', 'product_id');
    }
}
