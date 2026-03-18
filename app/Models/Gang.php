<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gang extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'boss_name',
        'contact_discord',
        'dirty_balance',
        'dirty_received_total',
        'cleaned_total',
        'commission_paid_total',
        'status',
    ];

    protected $casts = [
        'dirty_balance' => 'decimal:2',
        'dirty_received_total' => 'decimal:2',
        'cleaned_total' => 'decimal:2',
        'commission_paid_total' => 'decimal:2',
    ];

    public function holding()
{
    return $this->hasOne(\App\Models\Holding::class);
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
