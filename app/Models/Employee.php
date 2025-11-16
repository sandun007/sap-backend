<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'position',
        'salary',
        'address',
        'date_of_birth',
        'department_id',
        'manager_id',
        'is_active',
    ];

}
