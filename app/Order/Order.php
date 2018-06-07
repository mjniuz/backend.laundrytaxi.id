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

    public function getStatusTextAttribute(){
        if($this->status == 'rejected'){
            return '<span class="label label-danger">reject</span>';
        }
        if(!is_null($this->delivered_at)){
            return '<span class="label label-success">delivered</span>';
        }
        if(!is_null($this->process_at)){
            return '<span class="label label-info">on process</span>';
        }
        if(!is_null($this->pickup_at)){
            return '<span class="label label-primary">pickup</span>';
        }


        return '<span class="label label-warning">approved</span>';
    }
}