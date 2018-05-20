<?php

namespace App\Order;

class OrderRepository{
    public function find($id = null){
        return Order::with(['user','merchant'])->find($id);
    }

    public function listOrderByUserId($userId = null){
        return Order::with(['user','merchant'])->where('user_id', $userId)->orderBy('id','desc')->get();
    }

    public function create($user, $merchant, $data){
        $order  = new Order();
        $order->invoice_no  = $this->_generateInvoiceNo();
        $order->user_id     = $user->id;
        $order->merchant_id = $merchant->id;
        $order->full_name   = $data->full_name;
        $order->phone       = $data->phone;
        $order->estimate_weight = $data->estimate;
        $order->package     = $data->package;
        $order->lat         = $data->lat;
        $order->lng         = $data->lng;
        $order->full_address = $data->full_address;
        $order->address_note = $data->address_note;
        $order->note        = $data->note;
        $order->status      = "approved";
        $order->delivery_fee = 0;
        $order->grand_total = $this->_calculateGrandTotal($order);

        $order->rating      = '';
        $order->save();

        return $order;
    }

    private function _generateInvoiceNo(){
        $lastOrder  = Order::with([])->orderBy('id','desc')->first();

        if(!$lastOrder){
            return "INV1";
        }

        return "INV" . ($lastOrder->id + 1);
    }

    private function _calculateGrandTotal($order){
        if(is_null($order->pickup_at)){
            return 0;
        }

        $package    = 6000;
        if($order->package == 8000){
            $package    = 8000;
        }

        if($order->package == 10000){
            $package    = 10000;
        }

        return $package * $order->actual_weight + $order->delivery_fee;
    }
}