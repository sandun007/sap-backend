<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id'; // your table uses customer_id
    protected $fillable = [
        'customer_code',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'address',
        'customer_type',
        'registration_date',
        'credit_limit',
    ];
}
