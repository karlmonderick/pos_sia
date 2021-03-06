<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use Notifiable;


    protected $fillable = [
        'name', 'price', 'description'
    ];

    public function inventory(){
        return $this->hasMany('App\Inventory');
    }

}
