<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashRollDelivery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'gang_id',
        'holding_id',
        'delivery_number',
        'amount',
        'status',
        'delivered_by',
        'received_by',
        'delivered_at',
        'received_at',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'delivered_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function gang()
    {
        return $this->belongsTo(Gang::class);
    }

    public function holding()
    {
        return $this->belongsTo(Holding::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
