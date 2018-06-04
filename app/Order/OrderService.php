<?php

namespace App\Order;

use App\Merchant\MerchantRepository;
use App\SmsGateway\ZenzivaService;
use App\User\UserRepository;

class OrderService extends OrderRepository{
    protected $userRepo, $sms;
    public function __construct(UserRepository $userRepo, ZenzivaService $sms) {
        $this->userRepo = $userRepo;
        $this->sms      = $sms;
    }

    public function makeOrder($input){
        // find or create user
        $token          = !empty($input->remember_token) ? $input->remember_token : null;
        $packageId      = !empty($input->package_id) ? ($input->package_id - 1) : 99999;
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

        // check package
        $package        = $this->_allPackage();
        if(empty($package[$packageId])){
            return false;
        }
        $package        = $package[$packageId];

        // create order
        $order          = $this->create($user, $merchant, $input, $package);
        if($order){
            // send sms
            $sms    = new ZenzivaService();
            $sms->customerOrderSMS($user);
            $sms->merchantOrderSMS($merchant, $user);
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

    public function needForValidateUserPhone($fullName, $stdPhone){
        $input  = new \stdClass();
        $input->phone       = $stdPhone;
        $input->full_name   = $fullName;

        $user   = $this->userRepo->createUpdate($input);
        if(is_null($user->activated)){
            if(is_null($user->activate_code_expired) || date("Y-m-d H:i:s") >= $user->activate_code_expired){
                $user           = $this->userRepo->createPhoneValidation($user);
            }

            // send sms
            $this->sms->validationPhone($user);


            return true;
        }

        return false;
    }

    public function resendActivationCode($phone){
        $user           = $this->userRepo->findByPhone($phone);
        if(!$user OR !$user->validated){
            if(date("Y-m-d H:i:s") >= $user->activate_code_expired){
                $user           = $this->userRepo->createPhoneValidation($user);
            }

            // send sms
            $this->sms->validationPhone($user);


            return true;
        }

        return false;
    }

    public function submitValidationCode($phone, $code){
        $user           = $this->userRepo->findByPhone($phone);
        if($user AND !$user->validated){
            if(date("Y-m-d H:i:s") >= $user->activate_code_expired){
                return [
                    'status'    => false,
                    'message'   => 'Kode Expired, silahkan coba lagi',
                    'user'      => $user
                ];
            }
            if($user->activate_code == $code){
                $this->userRepo->activateUser($user);
                return [
                    'status'    => true,
                    'message'   => 'Validation Success',
                    'user'      => $user
                ];
            }
        }

        if($user AND $user->validated){
            $this->userRepo->activateUser($user);
            return [
                'status'    => true,
                'message'   => 'Validation Success',
                'user'      => $user
            ];
        }

        return [
            'status'    => false,
            'message'   => 'Terjadi kesalahan, silahkan coba lagi atau hubungi Admin info@laundrytaxi.id',
            'user'      => $user
        ];
    }

    public function getPackages($token = ''){
        if(!$token){
            // default package
            return $this->_defaultPackages();
        }

        $user           = $this->userRepo->findByToken($token);
        if($user){
            $hasUsedPromo   = $this->findOrderPromoByUserId($user->id, '1000_per_kg');
            if($hasUsedPromo){
                // default package
                return $this->_defaultPackages();
            }

            return $this->_packageAddPromo();
        }

        return $this->_defaultPackages();
    }

    public function findAndValidatePackage($token = '', $packageId = null){
        if(!$token){
            // default package
            return $this->_defaultPackages();
        }

        $user           = $this->userRepo->findByToken($token);
        if(!in_array($packageId, [1,2,3,4]) || !$user){
            return [
                'status'    => false,
                'message'   => 'Paket atau user tidak ditemukan, silahkan kembali dan periksa paket Anda'
            ];
        }
        if($user){
            $hasUsedPromo   = $this->findOrderPromoByUserId($user->id, '1000_per_kg');
            if($hasUsedPromo AND $packageId == 1){
                return [
                    'status'    => false,
                    'message'   => 'Paket promo tidak berlaku untuk saat ini, silahkan coba paket lainya'
                ];
            }

        }

        return [
            'status'    => true,
            'message'   => $this->_allPackage()[($packageId-1)]
        ];
    }

    private function _packageAddPromo(){
        return [
            $this->_allPackage()[3]
        ];
    }

    private function _defaultPackages(){
        return [
            $this->_allPackage()[0],
            $this->_allPackage()[1],
            $this->_allPackage()[2]
        ];
    }
}