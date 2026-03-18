<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'gang_id',
        'holding_id',
        'company_id',
        'invoice_number',
        'internal_reference',
        'concept',
        'description',
        'gross_amount',
        'issued_at',
        'due_at',
        'status',
        'is_generated_image',
        'public_image_path',
        'pdf_path',
        'created_by',
        'approved_by',
        'approved_at',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'issued_at' => 'date',
        'due_at' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'is_generated_image' => 'boolean',
    ];

    public function gang()
    {
        return $this->belongsTo(Gang::class);
    }

    public function holding()
    {
        return $this->belongsTo(Holding::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function settlement()
    {
        return $this->hasOne(Settlement::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
