<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckOut extends Model
{
    protected $fillable = [
        'receipt_no', 'client_id',
        'cart', 'total_amount',
        'branch_id',
    ];
}
