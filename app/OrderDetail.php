<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_detail';
    protected $primaryKey = "order_detail_id";
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Product','product_id');
    }
}
