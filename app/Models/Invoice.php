<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'invoice_number',
        'branch_id',
        'net_total',
        'payment_method',
        'credit_note_code',
        'credit_amount_used',
        'cash_given',
        'balance_returned',
        'status',
    ];

    protected $appends = ['balance_return'];

    public function getBalanceReturnAttribute()
    {
        return $this->balance_returned;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'invoice_id');
    }
}