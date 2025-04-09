<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale_payments extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class, 'accountID');
    }

    public function bill()
    {
        return $this->belongsTo(sales::class, 'salesID');
    }
}
