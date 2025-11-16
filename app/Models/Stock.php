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
        'quantity',
        'minimum_stock_level',
        'maximum_stock_level',
        'last_restocked_date',
        'location_in_warehouse',
    ];


}
