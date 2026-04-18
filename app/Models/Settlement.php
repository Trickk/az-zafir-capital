<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settlement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'gang_id',
        'holding_id',
        'settlement_number',
        'gross_amount',
        'commission_percent',
        'commission_amount',
        'net_amount',
        'status',
        'processed_at',
        'released_at',
        'processed_by',
        'released_by',
        'notes',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function gang()
    {
        return $this->belongsTo(Gang::class);
    }

    public function holding()
    {
        return $this->belongsTo(Holding::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function releaser()
    {
        return $this->belongsTo(User::class, 'released_by');
    }
}
