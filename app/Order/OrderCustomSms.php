<?php

namespace App\Order;

use Illuminate\Database\Eloquent\Model;

class OrderCustomSms extends Model{
    protected $table = 'order_custom_sms';
    public $timestamps = true;

    public function order(){
        return $this->belongsTo('App\Order\Order');
    }
}