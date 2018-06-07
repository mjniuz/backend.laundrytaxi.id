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

    public function updateStatusOrder($id, $inputs){
        $result     = $this->updateStatus($id, $inputs);
        if(!$result){
            return false;
        }

        $user   = $result['order']->user;
        $order  = $result['order'];
        switch ($result['status']){
            case 'pickup_at':
                $this->sms->courierPickedUp($user, $order);
                return true;
            case 'process_at':
                $delivery   = !empty($inputs['delivery_date']) ? $inputs['delivery_date'] : ' sesuai estimasi order ';
                $this->sms->onProcess($user, $order, $delivery);
                return true;
            case 'delivered_at':
                $this->sms->delivered($user, $order);
                return true;
        }

        return false;
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
            if($order->actual_weight > 0){
                // create by admin
                $input->status  = 'pickup_at';
                $this->updateStatus($order->id, $input);
                $sms->updateOrderPickup($user, $order);
            }else{
                $sms->customerOrderSMS($user);
                $sms->merchantOrderSMS($merchant, $user);
            }
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

    public function needForValidateUserPhone($fullName, $stdPhone, $rememberToken){
        $input  = new \stdClass();
        $input->phone       = $stdPhone;
        $input->full_name   = $fullName;

        $userFromToken           = $this->userRepo->findByToken($rememberToken);
        if(!$userFromToken OR $userFromToken->phone != $stdPhone){
            $user               = $this->userRepo->createUpdate($input);
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
            $hasOrder   = $this->getOrderApprovedByUserId($user->id);
            $count      = $hasOrder->count();
            if($count){
                // default package
                return $this->_packagePromoTransaction($count);
            }

            return $this->_packageAddPromo();
        }

        return $this->_defaultPackages();
    }

    public function rejectOrder($orderId = null, $reason = ''){
        $order  = $this->find($orderId);
        if(!$order){
            return false;
        }

        $order->status          = "rejected";
        $order->success_comment = $reason ? $reason : "tidak terjangkau lokasi";
        $order->save();

        $this->sms->rejectOrder($order->user, $order);

        return $order;
    }

    public function pickupSuccess($orderId, $weight = 0,$count = 0){
        $order  = $this->find($orderId);
        if(!$order){
            return false;
        }

        $result     = $this->updateActualOrder($order, $weight, $count);
        if($result){
            $this->sms->courierPickedUp($order->user, $result);
        }

        return $result;

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
            $this->_allPackage()[8],    // 1000 cuci lipat
            $this->_allPackage()[7]     // 4000 cuci gosok
        ];
    }

    private function _defaultPackages(){
        return [
            $this->_allPackage()[0],
            $this->_allPackage()[11]
        ];
    }

    private function _packagePromoTransaction($hasOrderTimes = 1){
        switch ($hasOrderTimes){
            case 1:
                return [
                    $this->_allPackage()[9],    // 2000 cuci lipat
                    $this->_allPackage()[10],   // 5000 cuci gosok
                    $this->_allPackage()[11]    // 7000 cuci kucek
                ];
            case 2:
                return [
                    $this->_allPackage()[5],    // 3000 cuci lipat
                    $this->_allPackage()[10],   // 5000 cuci gosok
                    $this->_allPackage()[11]    // 7000 cuci kucek
                ];
            case 3:
                return [
                    $this->_allPackage()[5],    // 3000 cuci lipat
                    $this->_allPackage()[10],   // 5000 cuci gosok
                    $this->_allPackage()[11]    // 7000 cuci kucek
                ];
            default:
                return [
                    $this->_allPackage()[5],    // 3000 cuci lipat
                    $this->_allPackage()[10],   // 5000 cuci gosok
                    $this->_allPackage()[11]    // 7000 cuci kucek
                ];
        }
    }
}