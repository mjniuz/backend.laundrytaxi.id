<?php

namespace App\Merchant;

use App\Balance\BalanceHistory;
use App\SmsGateway\ZenzivaService;
use App\User\PhoneNumberService;

class MerchantRepository{
    public function createUpdate($id = null, $inputs){
        $merchant   = $this->find($id);
        if(!$merchant){
            $merchant   = new Merchant();
        }

        $phone      = new PhoneNumberService();

        $merchant->name     = $inputs['name'];
        $merchant->phone    = $phone->standardPhone($inputs['phone']);
        $merchant->real_phone   = $phone->standardPhone($inputs['real_phone']);
        $merchant->email    = $inputs['email'];
        $merchant->address  = $inputs['address'];
        $merchant->avatar   = $inputs['avatar'];
        $merchant->password = '';
        $merchant->save();

        return $merchant;
    }

    public function find($id = null){
        return Merchant::with(['orders','balance_histories.order'])->find($id);
    }

    public function get($filters = []){
        return Merchant::with([])->paginate(20);
    }

    public function getAll(){
        return Merchant::with([])->orderBy('id','desc')->get();
    }

    public function addTransactionBalance($merchantId , $inputs){
        $merchant   = $this->find($merchantId);
        if(!$merchant){
            $result     = [
                'status'    => false,
                'message'   => 'Merchant tidak ditemukan!'
            ];

            return $result;
        }

        $inOut      = $inputs['in_out'];
        $message    = $inputs['message'];
        $amount     = parseFloatComma($inputs['amount']);

        if(!$message){
            $result     = [
                'status'    => false,
                'message'   => 'Pesan Tidak diisi!'
            ];

            return $result;
        }

        if(!in_array($inOut,['in','out'])){
            $result     = [
                'status'    => false,
                'message'   => 'Type salah!'
            ];

            return $result;
        }

        if($inOut == 'out' AND $amount > $merchant->balance){
            $result     = [
                'status'    => false,
                'message'   => 'Saldo merchant tidak mencukupi!'
            ];

            return $result;
        }

        $balanceHistory = new BalanceHistory();
        $balanceHistory->in_out = $inOut;
        $balanceHistory->merchant_id    = $merchant->id;
        $balanceHistory->order_id       = !empty($inputs['order_id']) ? $inputs['order_id'] : null;
        $balanceHistory->admin_user_id  = \Sentinel::check()->id;
        $balanceHistory->message        = $message;
        $balanceHistory->amount         = $amount;
        if($balanceHistory->in_out == 'in'){
            $balanceHistory->balance_total  = $merchant->balance + $amount;
        }else{
            // out
            $balanceHistory->balance_total  = $merchant->balance - $amount;
        }
        $balanceHistory->save();

        $merchant->balance  = $balanceHistory->balance_total;
        $merchant->save();

        $typeIdn    = ($inOut == 'out') ? "keluar" : "masuk";
        $messageSms = "Hallo merchant, telah terjadi transaksi sebesar " . number_format($amount,0) . " berupa saldo " . $typeIdn . ", dan sekarang sisa saldo Anda sebesar " . number_format($merchant->balance) . ". by LaundryTaxi";
        $sms        = new ZenzivaService();
        $sms->send($messageSms, $merchant->phone);

        return [
            'status'    => true,
            'message'   => "Update sukses"
        ];
    }

    public function getBalances($filters = []){
        $balances   = BalanceHistory::with([]);
        if(!empty($filters['merchant_id'])){
            $balances   = $balances->where('merchant_id', $filters['merchant_id']);
        }

        return $balances->orderBy('id','desc')->paginate(25);
    }
}