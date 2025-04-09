<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeBusiness($query)
    {
        return $query->where('type', 'Business');
    }

    public function scopeCustomer($query)
    {
        return $query->where('type', 'Customer');
    }

    public function scopeVendor($query)
    {
        return $query->where('type', 'Vendor');

    }

    public function transactions()
    {
        return $this->hasMany(transactions::class, 'accountID');
    }

    public function sale()
    {
        return $this->hasMany(sales::class, 'customerID');
    }
}
