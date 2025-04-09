<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockTransfer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function from()
    {
        return $this->belongsTo(warehouses::class, 'fromID');
    }
    public function to()
    {
        return $this->belongsTo(warehouses::class, 'toID');
    }
    public function user()
    {
        return $this->belongsTo(user::class, 'userID');
    }

    public function details()
    {
        return $this->hasMany(stockTransferDetails::class, 'transferID');
    }
}
