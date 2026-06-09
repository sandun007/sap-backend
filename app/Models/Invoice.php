<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'invoice_number',
        'net_total',
        'payment_method',
        'cash_given',
        'balance_returned',
        'status',
    ];

    protected $appends = ['balance_return'];

    public function getBalanceReturnAttribute()
    {
        return $this->balance_returned;
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }
}