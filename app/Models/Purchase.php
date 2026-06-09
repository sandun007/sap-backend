<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'branch_id',
        'purchase_date',
        'reference_no',
        'subtotal',
        'tax_amount',
        'discount',
        'grand_total',
        'payment_method',
        'amount_paid',
        'balance_due',
        'payment_notes',
        'notes',
        'bank_name',
        'bank_reference',
        'card_type',
        'card_last_four',
        'loan_interest_rate',
        'loan_term_months',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_record_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }
}
