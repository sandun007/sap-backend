<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'branch_id';
    public $incrementing = true;
    protected $keyType = 'int';
    use HasFactory;
    protected $fillable = [
        'branch_name',
        'address',
        'phone',
        'email',
        'manager_id',
        'opening_date',
        'is_active',
    ];
}
