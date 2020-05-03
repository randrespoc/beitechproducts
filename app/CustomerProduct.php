<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Product;

class CustomerProduct extends Model
{
    protected $table = 'customer_product';
    public $timestamps = false;
}
