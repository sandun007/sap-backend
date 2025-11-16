<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'subcategory_id';

    protected $fillable = [
        'subcategory_name',
        'description',
        'category_id',
        'created_date',
    ];


}
