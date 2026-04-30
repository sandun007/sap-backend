<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{


    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
   'store_code',
   'store_name'
];
}
