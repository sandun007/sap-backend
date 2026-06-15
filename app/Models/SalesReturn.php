<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $table = 'sales_returns';
    protected $primaryKey = 'return_id';

    protected $fillable = [
        'return_number',
        'invoice_id',
        'refund_amount',
        'refund_method',
        'reason',
        'created_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class, 'return_id', 'return_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
