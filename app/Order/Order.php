<?php

namespace App\Order;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{
    protected $table = 'order';
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User\User');
    }

    public function merchant(){
        return $this->belongsTo('App\Merchant\Merchant');
    }

    public function getDateAttribute(){
        return date("j f Y", strtotime($this->created_at));
    }
}