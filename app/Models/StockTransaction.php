<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'stock_id',
        'product_id',
        'branch_id',
        'brand_id',
        'category_id',
        'subcategory_id',
        'product_name',
        'transaction_type',
        'quantity_before',
        'quantity_changed',
        'quantity_after',
        'reference_number',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_changed' => 'integer',
        'quantity_after' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the stock associated with this transaction
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'stock_id');
    }

    /**
     * Get the product associated with this transaction
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Get the branch associated with this transaction
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    /**
     * Get the brand associated with this transaction
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    /**
     * Get the category associated with this transaction
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /**
     * Get the subcategory associated with this transaction
     */
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'subcategory_id');
    }

    /**
     * Get the user who created this transaction
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}