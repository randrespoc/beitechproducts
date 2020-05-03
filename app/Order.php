<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = "order_id";
    public $timestamps = false;

    public function orderDetails()
    {
        return $this->hasMany('App\OrderDetail','order_id');
    }
}
