<?php

namespace App\Order;

use App\User\PhoneNumberService;
use Sentinel;

class OrderRepository{
    public function find($id = null){
        return Order::with(['user','merchant'])->find($id);
    }

    public function getSms($id){
        return OrderCustomSms::with([])->where('order_id', $id)->orderBy('id','desc')->get();
    }

    public function listOrderByUserId($userId = null){
        return Order::with(['user','merchant'])->where('user_id', $userId)->orderBy('id','desc')->get();
    }

    public function get($filters = []){
        $orders     = Order::with(['user']);
        if(!empty($filters['invoice_no'])){
            $orders->where('invoice_no', 'like', $filters['invoice_no']);
        }

        if(!empty($filters['name'])){
            $name   = $filters['name'];
            $orders->where(function($q) use($name){
                $q->where('full_name', 'like', $name)
                    ->orWhereHas('user', function ($qUser) use($name){
                        $qUser->where('name','like',$name);
                    });
            });
        }


        if(!empty($filters['phone'])){
            $phoneValid     = new PhoneNumberService();

            $phone  = $phoneValid->standardPhone($filters['phone']);
            $orders->where('phone','like', $phone);
        }

        if(!empty($filters['address'])){
            $address   = $filters['address'];
            $orders->where(function($q) use($address){
                $q->where('full_address', 'like', $address)
                    ->orWhere('address_note', $address);
            });
        }

        if(!empty($filters['status'])){
            $orders->where('status', 'like', $filters['status']);
        }

        if(!empty($filters['process'])){
            $process    = $filters['process'];
            switch ($process){
                case 'pickup_at':
                    $orders->whereNotNull('pickup_at');
                    break;
                case 'process_at':
                    $orders->whereNotNull('process_at');
                    break;
                case 'delivered_at':
                    $orders->whereNotNull('delivered_at');
                    break;
                default:
                    break;
            }
        }

        if(!empty($filters['date_start'])){
            $dateStart      = date("Y-m-d 00:00:00", strtotime($filters['date_start']));
            $dateEnd        = !empty($filters['date_end']) ? date("Y-m-d 23:59:59", strtotime($filters['date_end'])) : date("Y-m-d 23:59:59");
            $dateType       = !empty($filters['date_type']) ? $filters['date_type'] : 'created_at';
            switch ($dateType){
                case 'created_at':
                    $orders->whereBetween('created_at', [$dateStart,$dateEnd]);
                    break;
                case 'pickup_at':
                    $orders->whereBetween('pickup_at', [$dateStart,$dateEnd]);
                    break;
                case 'process_at':
                    $orders->whereBetween('process_at', [$dateStart,$dateEnd]);
                    break;
                case 'delivered_at':
                    $orders->whereBetween('delivered_at', [$dateStart,$dateEnd]);
                    break;
            }
        }

        return $orders->orderBy('id','desc')->paginate(25);
    }

    public function updateStatus($id,$inputs){
        $status     = is_array($inputs) ? $inputs['status'] : $inputs->status;
        $order      = $this->find($id);

        if(!$order || empty($inputs)){
            return false;
        }

        if($status == 'pickup_at' && is_null($order->pickup_at)){
            $order->pickup_at = date("Y-m-d H:i:s");
            // update here
            $weight = is_array($inputs) ? $inputs['actual_weight'] : $inputs->actual_weight;
            $count  = is_array($inputs) ? $inputs['actual_count'] : $inputs->actual_count;
            $this->updateActualOrder($order, $weight, $count);
        }

        if($status == 'process_at' && is_null($order->process_at) && !is_null($order->pickup_at)){
            $order->process_at  = date("Y-m-d H:i:s");
        }

        if($status == 'delivered_at' && is_null($order->delivered_at) && !is_null($order->process_at)){
            $order->delivered_at = date("Y-m-d H:i:s");
        }

        $order->save();

        return [
            'status'    => $status,
            'order'     => $order
        ];
    }

    public function create($user, $merchant, $data, $package){
        $order  = new Order();
        $order->invoice_no  = $this->_generateInvoiceNo();
        $order->user_id     = $user->id;
        $order->merchant_id = $merchant->id;
        $order->estimate_weight = !empty($data->estimate) ? $data->estimate : $data->actual_weight;
        if(!empty($data->actual_weight)){
            $order->actual_weight   = $data->actual_weight;
            $order->pickup_at       = date("Y-m-d H:i:s");
        }
        if(!empty($data->actual_count)){
            $order->actual_count    = $data->actual_count;
        }
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

    public function getOrderApprovedByUserId($userId = null){
        return Order::with([])->where('user_id', $userId)->where('status','<>', 'rejected')->get();
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

    public function generateOrderDetail($order, $package) {
        $weight     = !empty($order->actual_weight) ? $order->actual_weight : $order->estimate_weight;
        $grand      = $this->_calculateGrandTotal($order, $package);
        $results    = [];

        $results[]     = [
            'title'     => "Paket ". $package['name'] ." @" . number_format($order->actual_weight,0) . "Kg" . (($package['max'] > 0) ? ", max " . $package['max'] . "kg" : ""),
            'price_text'=> number_format($grand['weight_price']),
            'price'     => $grand['weight_price']
        ];
        if($grand['over_max']){
            $results[]    = [
                'title'     => 'Biaya lebih ' . ($weight - $package['max']) . 'kg @' . $package['after_max'] . '/kg',
                'price_text'=> number_format($grand['max_price'],0),
                'price'     => $grand['max_price']
            ];
        }

        $results[] = [
            'title'     => "Biaya Pickup (PROMO)",
            'price_text'=> 0,
            'price'     => 0
        ];

        return $results;
    }

    public function findLastOrderByUserId($userId){
        return Order::with(['user'])->where('user_id', $userId)->orderBy('id','desc')->first();
    }

    public function _allPackage(){
        return [
            [
                'id'    => 1,
                'name'  => 'Cuci Gosok 6rb/kg 2 Hari',
                'description'   => 'Cuci Gosok diproses dalam 2 hari kerja, Gratis antar jemput',
                'price_per_kg'  => 6000,
                'merchant_fee_per_kg' => 3500,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '',
                'key'   => '6000_per_kg_cuci_gosok'
            ],
            [
                'id'    => 2,
                'name'  => 'Cuci Gosok 7rb/kg 2 Hari',
                'description'   => 'Cuci Gosok diproses dalam 2 hari kerja, Gratis antar jemput',
                'price_per_kg'  => 7000,
                'merchant_fee_per_kg' => 3500,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '',
                'key'   => '7000_per_kg'
            ],
            [
                'id'    => 3,
                'name'  => 'Cuci Gosok Expr 8rb/kg 1 Hari',
                'description'   => 'Cuci Gosok Ekspress diproses dalam 1 hari kerja',
                'price_per_kg'  => 8000,
                'merchant_fee_per_kg' => 4500,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '',
                'key'   => '8000_per_kg'
            ],
            [
                'id'    => 4,
                'name'  => 'Cuci Gosok 1rb/kg 2 Hari',
                'description'   => 'Promo pendaftaran pertama Cuci Gosok 1000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 5000/kg setelahnya',
                'price_per_kg'  => 1000,
                'merchant_fee_per_kg' => 3500,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 5000,
                'promo' => '1000_per_kg',
                'key'   => '1000_per_kg'
            ],
            [
                'id'    => 5,
                'name'  => 'Cuci Gosok 2rb/kg 2 Hari',
                'description'   => 'Promo pendaftaran trx ke 2 Cuci mesin Gosok 2000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 6000/kg setelahnya',
                'price_per_kg'  => 2000,
                'merchant_fee_per_kg' => 3500,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 6000,
                'promo' => '2000_per_kg',
                'key'   => '2000_per_kg'
            ],
            [
                'id'    => 6,
                'name'  => 'Cuci Lipat 3rb/kg 2 Hari',
                'description'   => 'Promo pendaftaran trx ke 3 Cuci mesin & Lipat saja 3000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 5000/kg setelahnya',
                'price_per_kg'  => 3000,
                'merchant_fee_per_kg' => 2000,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 5000,
                'promo' => '3000_per_kg_cuci_lipat',
                'key'   => '3000_per_kg_cuci_lipat'
            ],
            [
                'id'    => 7,
                'name'  => 'Cuci Lipat 4rb/kg 2 Hari',
                'description'   => 'Promo pendaftaran trx ke 4 Cuci mesin & Lipat saja 4000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 5000/kg setelahnya',
                'price_per_kg'  => 4000,
                'merchant_fee_per_kg' => 2000,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 5000,
                'promo' => '4000_per_kg',
                'key'   => '4000_per_kg'
            ],
            [
                'id'    => 8,
                'name'  => 'Cuci Gosok 4rb/kg 2 Hari',
                'description'   => 'Promo Cuci mesin & Gosok 4000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 6000/kg setelahnya',
                'price_per_kg'  => 4000,
                'merchant_fee_per_kg' => 3500,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 6000,
                'promo' => '4000_per_kg_cuci_gosok',
                'key'   => '4000_per_kg_cuci_gosok'
            ],
            [
                'id'    => 9,
                'name'  => 'Cuci Lipat 1rb/kg 2 Hari',
                'description'   => 'Cuci Mesin & Lipat saja 1000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 5000/kg setelahnya',
                'price_per_kg'  => 1000,
                'merchant_fee_per_kg' => 2000,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 5000,
                'promo' => '1000_per_kg_cuci_mesin_lipat',
                'key'   => '1000_per_kg_cuci_mesin_lipat'
            ],
            [
                'id'    => 10,
                'name'  => 'Cuci Lipat 2rb/kg 2 Hari',
                'description'   => 'Promo pendaftaran Cuci mesin & Lipat saja 2000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 5000/kg setelahnya',
                'price_per_kg'  => 2000,
                'merchant_fee_per_kg' => 2000,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 5000,
                'promo' => '2000_per_kg_cuci_lipat',
                'key'   => '2000_per_kg_cuci_lipat'
            ],
            [
                'id'    => 11,
                'name'  => 'Cuci Gosok 5rb/kg 2 Hari',
                'description'   => 'Promo Cuci mesin & Gosok 5000/kg, gratis antar jemput, max 10kg, jika lebih dari 10kg, akan dikenakan 6000/kg setelahnya',
                'price_per_kg'  => 5000,
                'merchant_fee_per_kg' => 3500,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 6000,
                'promo' => '5000_per_kg',
                'key'   => '5000_per_kg'
            ],
            [
                'id'    => 12,
                'name'  => 'Cuci Kucek 7rb/kg 2 Hari',
                'description'   => 'Promo Cuci KUCEK & Gosok 7000/kg (Lebih bersih di sela-sela baju), gratis antar jemput. Dikerjakan oleh tenaga cuci profesional dengan tangan, tanpa mesin',
                'price_per_kg'  => 7000,
                'merchant_fee_per_kg' => 5000,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '7000_per_kg_cuci_gosok_kucek',
                'key'   => '7000_per_kg_cuci_gosok_kucek'
            ],
            [
                'id'    => 13,
                'name'  => 'Cuci Kucek 10rb/kg 2 Hari',
                'description'   => 'Promo Cuci KUCEK & Gosok 10.000/kg (Lebih bersih di sela-sela baju), gratis antar jemput. Dikerjakan oleh tenaga cuci profesional dengan tangan, tanpa mesin',
                'price_per_kg'  => 10000,
                'merchant_fee_per_kg' => 5000,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '10000_per_kg_cuci_gosok_kucek',
                'key'   => '10000_per_kg_cuci_gosok_kucek'
            ],
            [
                'id'    => 14,
                'name'  => 'Gosok saja 3rb/kg 2 Hari',
                'description'   => 'Promo Jasa Gosok baju yang telah Anda cuci dirumah, lebih rapi dan wangi tanpa ribet',
                'price_per_kg'  => 3000,
                'merchant_fee_per_kg' => 2000,
                'min'   => 0,
                'max'   => 10,
                'after_max' => 4000,
                'promo' => '3000_per_kg_gosok_saja',
                'key'   => '3000_per_kg_gosok_saja'
            ],
            [
                'id'    => 15,
                'name'  => 'Gosok saja 4000/kg 2 Hari',
                'description'   => 'Jasa Gosok baju yang telah Anda cuci dirumah, lebih rapi dan wangi tanpa ribet',
                'price_per_kg'  => 4000,
                'merchant_fee_per_kg' => 2000,
                'min'   => 0,
                'max'   => 0,
                'after_max' => 0,
                'promo' => '4000_per_kg_gosok_saja',
                'key'   => '4000_per_kg_gosok_saja'
            ],
        ];
    }

    public function createCustomSms($orderId,$message,$phone){
        $sms    = new OrderCustomSms();
        $sms->order_id  = $orderId;
        $sms->admin_user_id = Sentinel::check()->id;
        $sms->message       = $message;
        $sms->destination_number       = $phone;
        $sms->save();

        return $sms;
    }
}