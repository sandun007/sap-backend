<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;
    protected $primaryKey = 'brand_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'brand_id',
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

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($brand) {
//            if (empty($brand->brand_id)) {
//                do {
//                    $number = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
//                    $random = strtoupper(Str::random(4));
//                    $brand->brand_id = "BRAND-{$number}-{$random}";
//
//                } while (self::where('brand_id', $brand->brand_id)->exists());
//            }
//        });
//    }
}
