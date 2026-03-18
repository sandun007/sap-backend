<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone_num1',
        'phone_num2',
        'nic_num',
        'nic_front',
        'nic_back',
        'hire_date',
        'position',
        'salary',
        'address',
        'date_of_birth',
        'department_id',
        'is_active',
    ];


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
