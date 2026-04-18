<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatrixFundMovement extends Model
{
    protected $fillable = [
        'gang_id',
        'matrix_fund_id',
        'type',
        'amount',
        'concept',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function gang()
    {
        return $this->belongsTo(Gang::class);
    }

    public function matrixFund()
    {
        return $this->belongsTo(MatrixFund::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
