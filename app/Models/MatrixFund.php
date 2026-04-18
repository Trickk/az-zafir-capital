<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatrixFund extends Model
{
    protected $fillable = [
        'gang_id',
        'balance',
        'total_in',
        'total_out',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_in' => 'decimal:2',
        'total_out' => 'decimal:2',
    ];

    public function gang()
    {
        return $this->belongsTo(Gang::class)->withTrashed();
    }

    public function movements()
    {
        return $this->hasMany(MatrixFundMovement::class);
    }
}
