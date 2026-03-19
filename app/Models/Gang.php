<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gang extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'boss_name',
        'contact_discord',
        'settlement_percent',
        'dirty_balance',
        'dirty_received_total',
        'cleaned_total',
        'commission_paid_total',
        'status',
    ];

    protected $casts = [
        'settlement_percent' => 'decimal:2',
        'dirty_balance' => 'decimal:2',
        'dirty_received_total' => 'decimal:2',
        'cleaned_total' => 'decimal:2',
        'commission_paid_total' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function cashDeliveries()
    {
        return $this->hasMany(CashDelivery::class);
    }
}
