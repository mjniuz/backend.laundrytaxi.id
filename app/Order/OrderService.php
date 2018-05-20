<?php

namespace App\Order;

use App\Merchant\MerchantRepository;
use App\SmsGateway\ZenzivaService;
use App\User\UserRepository;

class OrderService extends OrderRepository{
    protected $userRepo;
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function makeOrder($input){
        // find or create user
        $token          = !empty($input->remember_token) ? $input->remember_token : null;
        $user           = $this->userRepo->findByToken($token);
        if(!$user){
            $user           = $this->userRepo->findByPhone($input->phone);
        }
        // no user at all, create new
        if(!$user){
            $user           = $this->userRepo->createUpdate($input);
        }

        // find merchant
        $merchantRepo   = new MerchantRepository();
        $merchant       = $merchantRepo->find(1);   // hard code

        // create order
        $order          = $this->create($user, $merchant, $input);
        if($order){
            // send sms
            $sms    = new ZenzivaService();
            $sms->customerOrderSMS($user);
            $sms->merchantOrderSMS($merchant);
            return $this->find($order->id);
        }

        return $order;
    }

    public function getListByToken($rememberToken = ''){
        $user   = $this->userRepo->findByToken($rememberToken);
        if(!$user){
            return false;
        }

        return $this->listOrderByUserId($user->id);
    }

    public function findDetail($rememberToken = '', $orderId = null){
        $user   = $this->userRepo->findByToken($rememberToken);
        if(!$user){
            return false;
        }

        return $this->find($orderId);

    }
}