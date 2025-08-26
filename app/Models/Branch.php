<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'flag',
        'address',
        'location',
        'email',
        'phone_no_01',
        'phone_no_02',
        'website',
        'company_id',
        'status'
    ];
}
