<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gang extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'gang_code',
        'company_id',
        'name',
        'slug',
        'description',
        'boss_name',
        'contact_discord',
        'commission_percent',
        'dirty_balance',
        'dirty_received_total',
        'cleaned_total',
        'commission_paid_total',
        'status',
    ];

    protected $casts = [
        'commission_percent' => 'decimal:2',
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
