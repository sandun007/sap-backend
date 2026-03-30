<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;
    protected $primaryKey = 'brand_id';

    protected $fillable = [

        'brand_code',
        'brand_name',
        'contact_person',
        'phone',
        'email',
        'website',
        'created_date',
    ];

    protected $casts = [

        'created_date' => 'date',
    ];


    }

