<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sales extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(accounts::class, 'customerID');
    }

    public function details()
    {
        return $this->hasMany(sale_details::class, 'salesID');
    }

    public function payments()
    {
        return $this->hasMany(sale_payments::class, 'salesID');
    }


}
