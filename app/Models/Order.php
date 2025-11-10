<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_number',
        'order_date',
        'customer_id',
        'branch_id',
        'employee_id',
        'total_amount',
        'status',
        'payment_status',
        'shipping_address',
        'notes',
    ];


}
