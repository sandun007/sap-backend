<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'department_id';


    protected $fillable = [
        'department_name',
        'description',
        'manager_id',
        'branch_id',
        'created_date',
    ];




    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

}
