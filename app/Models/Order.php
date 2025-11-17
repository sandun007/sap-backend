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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

       public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

       public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }


}
