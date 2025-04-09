<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentReceiving extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fromAccount()
    {
        return $this->belongsTo(accounts::class, 'fromID');
    }

    public function inAccount()
    {
        return $this->belongsTo(accounts::class, 'toID');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
