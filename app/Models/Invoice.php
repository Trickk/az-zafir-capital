<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'internal_reference',
        'invoice_customer_name',
        'invoice_state_id',

        'public_token',

        'gang_name_snapshot',
        'gang_code_snapshot',

        'company_name_snapshot',
        'company_code_snapshot',
        'company_legal_name_snapshot',
        'company_type_snapshot',
        'company_country_snapshot',
        'company_city_snapshot',
        'company_address_snapshot',
        'company_tax_id_snapshot',
        'company_logo_path_snapshot',
        'company_invoice_image_path_snapshot',

        'concept',
        'description',

        'gross_amount',
        'settlement_percent',
        'commission_percent',
        'commission_amount',
        'net_amount',

        'issued_at',
        'due_at',

        'status',

        'is_generated_image',
        'public_image_path',
        'pdf_path',

        'png_path',
        'public_image_url',

        'created_by',
        'approved_by',
        'approved_at',
        'paid_at',
        'cancelled_at',

        'notes',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'settlement_percent' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'issued_at' => 'date',
        'due_at' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'is_generated_image' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
