<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_code',
        'name',
        'slug',
        'legal_name',
        'type',
        'country',
        'city',
        'address',
        'tax_id',
        'responsible_name',
        'description',
        'logo_path',
        'invoice_image_path',
        'status',
    ];

    public function gangs()
    {
        return $this->hasMany(Gang::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
