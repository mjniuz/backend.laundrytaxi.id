<?php

namespace App\Balance;

use App\User\PhoneNumberService;

class BalanceRepository{
    public function createUpdate($id = null, $inputs){
        $merchant   = $this->find($id);
        if(!$merchant){
            $merchant   = new BalanceHistory();
        }

        $phone      = new PhoneNumberService();

        $merchant->name     = $inputs['name'];
        $merchant->phone    = $phone->standardPhone($inputs['phone']);
        $merchant->email    = $inputs['email'];
        $merchant->address  = $inputs['address'];
        $merchant->avatar   = $inputs['avatar'];
        $merchant->save();

        return $merchant;
    }

    public function find($id = null){
        return BalanceHistory::with(['order','merchant','admin'])->find($id);
    }

    public function get($filters = []){
        return BalanceHistory::with(['order','merchant','admin'])->paginate(20);
    }
}