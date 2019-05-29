<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id', 'stock_quantity', 'stock_original', 'user_id'
    ];

    // public function getProduct(){
    //     return Product::where('id', $this->product_id)->first()->name;
    // }

    // public function user(){
    //     return $this->belongsTo('App\User');
    // }
}
