<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    use HasFactory;

    protected $table = 'sales_return_items';
    protected $primaryKey = 'return_item_id';

    protected $fillable = [
        'return_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'price',
        'total',
        'condition',
    ];

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class, 'return_id', 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
