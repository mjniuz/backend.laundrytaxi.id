<?php

namespace App\Balance;

use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model{
    protected $table = 'balance_history';
    public $timestamps = true;

    public function order(){
        return $this->belongsTo('App\Order\Order');
    }

    public function merchant(){
        return $this->belongsTo('App\Merchant\Merchant');
    }

    public function admin(){
        return $this->belongsTo('App\AdminUsers\AdminUser');
    }
}