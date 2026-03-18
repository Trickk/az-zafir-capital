<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'legal_name',
        'type',
        'country',
        'city',
        'address',
        'tax_id',
        'is_internal',
        'description',
        'logo_path',
        'status',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
