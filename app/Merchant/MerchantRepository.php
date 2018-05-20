<?php

namespace App\Merchant;

class MerchantRepository{
    public function createUpdate($data){

    }

    public function find($id = null){
        return Merchant::with([])->find($id);
    }
}