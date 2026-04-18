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
        'matrix_percent',
        'operating_balance',
        'status',
    ];

    protected $casts = [
        'commission_percent' => 'decimal:2',
        'matrix_percent' => 'decimal:2',
        'operating_balance' => 'decimal:2',
    ];

    // Relaciones

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function cashDeliveries()
    {
        return $this->hasMany(CashDelivery::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function matrixFund()
    {
        return $this->hasOne(MatrixFund::class);
    }

    public function matrixMovements()
    {
        return $this->hasMany(MatrixFundMovement::class);
    }
}
