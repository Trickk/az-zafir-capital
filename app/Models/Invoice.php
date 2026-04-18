<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',

        'gang_id',
        'company_id',

        'gang_name_snapshot',
        'company_name_snapshot',
        'company_legal_name_snapshot',
        'company_tax_id_snapshot',
        'company_responsible_name_snapshot',

        'company_logo_path_snapshot',
        'company_invoice_image_path_snapshot',

        'invoice_customer_name',
        'invoice_state_id',

        'concept',
        'description',
        'amount',

        'status',
        'issued_at',
        'paid_at',
        'cancelled_at',

        'created_by',

        'pdf_path',
        'image_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relaciones

    public function gang()
    {
        return $this->belongsTo(Gang::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
