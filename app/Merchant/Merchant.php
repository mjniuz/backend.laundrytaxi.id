<?php

namespace App\Merchant;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model{
    protected $table = 'merchant';
    public $timestamps = true;
    protected $hidden = array('password');

    public function orders(){
        return $this->hasMany('App\Order\Order')->orderBy('id','desc')->limit(25);
    }
    public function balance_histories(){
        return $this->hasMany('App\Balance\BalanceHistory')->orderBy('id','desc')->limit(25);
    }
}