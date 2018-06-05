<?php

namespace App\Order;

class OrderRepository{
    public function find($id = null){
        return Order::with(['user','merchant'])->find($id);
    }

    public function listOrderByUserId($userId = null){
        return Order::with(['user','merchant'])->where('user_id', $userId)->orderBy('id','desc')->get();
    }

    public function create($user, $merchant, $data, $package){
        $order  = new Order();
        $order->invoice_no  = $this->_generateInvoiceNo();
        $order->user_id     = $user->id;
        $order->merchant_id = $merchant->id;
        $order->estimate_weight = $data->estimate;
        $order->package_id      = $data->package_id;
        $order->lat         = $data->lat;
        $order->lng         = $data->lng;
        $order->full_address = $data->full_address;
        $order->full_name   = $data->full_name;
        $order->phone       = $user->phone;
        $order->address_note = $data->address_note;
        $order->note        = $data->note;
        $order->status      = "approved";
        $order->delivery_fee    = 0;

        $totalCalculate         = $this->_calculateGrandTotal($order, $package);
        $order->weight_price    = $totalCalculate['weight_price'];
        $order->max_price       = $totalCalculate['max_price'];
        $order->over_max        = $totalCalculate['over_max'];
        $order->grand_total     = $totalCalculate['grand_total'];
        $order->package         = $package['key'];

        $order->rating      = '';
        $order->save();

        return $order;
    }

    public function updateActualOrder($order, $actualWeight = 10, $count = 0){
        $package                = $this->_allPackage();
        if(empty($package[($order->package_id - 1)])){
            return false;
        }

        $package                = $package[($order->package_id - 1)];
        $order->actual_weight   = $actualWeight;
        $order->actual_count    = $count;
        $totalCalculate         = $this->_calculateGrandTotal($order, $package);
        $order->weight_price    = $totalCalculate['weight_price'];
        $order->max_price       = $totalCalculate['max_price'];
        $order->over_max        = $totalCalculate['over_max'];
        $order->grand_total     = $totalCalculate['grand_total'];
        $order->pickup_at       = date("Y-m-d H:i:s");

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

    public function _calculateGrandTotal($order, $package){
        if(!$package){
            return false;
        }
        $weight         = ($order->actual_weight > 0) ? $order->actual_weight : $order->estimate_weight;

        $grandTotal     = 0;
        $maxPrice       = 0;
        $overMax        = false;
        // min max
        if($package['max'] > 0 AND $package['max'] < $weight) {
            $overMax    = true;
            $weightPrice    = $package['max'] * $package['price_per_kg'];
            $maxPrice       = ($weight - $package['max']) * $package['after_max'];
            $grandTotal += $maxPrice;
            $grandTotal += $weightPrice;

        }else{
            $weightPrice    = $weight * $package['price_per_kg'];
            $grandTotal     += $weightPrice;
        }


        return [
            'grand_total'   => $grandTotal,
            'weight_price'  => $weightPrice,
            'max_price'     => $maxPrice,
            'over_max'      => $overMax,
            'weight'        => $weight
        ];
    }

    public function findOrderPromoByUserId($userId = null, $promo = '1000_per_kg'){
        return Order::with([])->where('user_id', $userId)->where('package', $promo)->first();
    }

    public function getOldPackage($package = 6000){
        if($package == 6000){
            return $this->_allPackage()[0];
        }
        if($package == 7000){
            return $this->_allPackage()[1];
        }

        // 8000
        return $this->_allPackage()[2];
    }

    public function _allPackage(){
        return [
            [
                'id'    => 1,
                'name'  => '6.000/kg 2 Hari',
                'description'   => 'Cuci Lipat diproses dalam 2 hari kerja',
                'price_per_kg'  => 6000,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '',
                'key'   => '6000_per_kg'
            ],
            [
                'id'    => 2,
                'name'  => '7.000/kg 2 Hari',
                'description'   => 'Cuci Gosok diproses dalam 2 hari kerja',
                'price_per_kg'  => 7000,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '',
                'key'   => '7000_per_kg'
            ],
            [
                'id'    => 3,
                'name'  => '8.000/kg 1 Hari',
                'description'   => 'Cuci Gosok Ekspress diproses dalam 1 hari kerja',
                'price_per_kg'  => 8000,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '',
                'key'   => '8000_per_kg'
            ],
            [
                'id'    => 4,
                'name'  => '1.000/kg 2 Hari',
                'description'   => 'Promo pendaftaran pertama Cuci Gosok 1000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 5000/kg setelahnya',
                'price_per_kg'  => 1000,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 5000,
                'promo' => '1000_per_kg',
                'key'   => '1000_per_kg'
            ]
        ];
    }
}