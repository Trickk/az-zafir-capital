<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holding extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'gang_id',
        'name',
        'slug',
        'legal_name',
        'sector',
        'contact_name',
        'contact_phone',
        'contact_email',
        'trust_level',
        'default_commission_percent',
        'dirty_balance',
        'cleaned_total',
        'commission_paid_total',
        'status',
        'notes',
    ];

    protected $casts = [
        'default_commission_percent' => 'decimal:2',
        'dirty_balance' => 'decimal:2',
        'cleaned_total' => 'decimal:2',
        'commission_paid_total' => 'decimal:2',
    ];

    public function gang()
    {
        return $this->belongsTo(Gang::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    public function cashRollDeliveries()
    {
        return $this->hasMany(CashRollDelivery::class);
    }
}
