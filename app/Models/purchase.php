<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(accounts::class, 'vendorID');
    }

    public function details()
    {
        return $this->hasMany(purchase_details::class, 'purchaseID');
    }

    public function payments()
    {
        return $this->hasMany(purchase_payments::class, 'purchaseID');
    }
}
