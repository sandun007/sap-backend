<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $primaryKey = 'stock_id';

    protected $fillable = [
        'product_id',
        'branch_id',
        'brand_id',
        'category_id',
        'subcategory_id',
        'product_name',
        'quantity',
        'minimum_stock_level',
        'maximum_stock_level',
        'last_restocked_date',
        'location_in_warehouse',
    ];

    protected $casts = [
        'last_restocked_date' => 'date',
        'quantity' => 'integer',
        'minimum_stock_level' => 'integer',
        'maximum_stock_level' => 'integer',
    ];

    /**
     * Get the product associated with this stock
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Get the branch associated with this stock
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    /**
     * Get the brand associated with this stock
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    /**
     * Get the category associated with this stock
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /**
     * Get the subcategory associated with this stock
     */
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'subcategory_id');
    }

    /**
     * Check if stock is low
     */
    public function isLowStock()
    {
        return $this->quantity <= $this->minimum_stock_level;
    }

    /**
     * Check if stock is at maximum
     */
    public function isMaxStock()
    {
        return $this->quantity >= $this->maximum_stock_level;
    }
}