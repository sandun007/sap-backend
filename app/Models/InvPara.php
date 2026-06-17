<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvPara extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_flag',
        'company_name',
        'company_address',
        'email',
        'phone_no_01',
        'phone_no_02',
        'website',
        'logo',
        'product_code',
        'brand_code',
        'category_code',
        'invoice_no',
        'grn_no',
        'branch_code',
        'bank_code',
        'customer_code',
        'employee_code',
        'supplier_code',
        'department_code',
        'expenses_code',
        'store_code',
        'status',
        'package',
        'auto_print_grn',
        'show_print_preview',
        'print_footer_text',
        'grn_print_size',
        'dashboard_logo_size'
    ];
}
