<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashDelivery extends Model
{
    protected $fillable = [
        'gang_id',
        'company_id',
        'delivery_number',
        'amount',

        'matrix_percent',
        'commission_percent',
        'operating_percent',

        'matrix_amount',
        'commission_amount',
        'operating_amount',

        'status',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'matrix_percent' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'operating_percent' => 'decimal:2',
        'matrix_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'operating_amount' => 'decimal:2',
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
