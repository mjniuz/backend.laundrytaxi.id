<?php

namespace App\Http\Controllers;

use App\Order\OrderService;
use App\User\PhoneNumberService;
use App\User\UserRepository;
use Illuminate\Http\Request;

class ApiController extends Controller{
    protected $order_service, $phone_validate, $user;
    public function __construct(OrderService $orderService, PhoneNumberService $phoneValidate, UserRepository $user) {
        $this->order_service    = $orderService;
        $this->phone_validate   = $phoneValidate;
        $this->user             = $user;
    }

    public function createOrder(Request $request){
        // ala-ala proses
        sleep(4);
        $data   = json_decode(json_encode($request->all()));

        $order  = $this->order_service->makeOrder($data);
        $orderId  = $order->id;


        $result   = $this->order_service->find($orderId);
        return response()->json([
            'status'    => (bool)$result,
            'data'      => $result
        ]);
    }

    public function orderList(Request $request){
        $rememberToken  = $request->get('remember_token');
        $result         = $this->order_service->getListByToken($rememberToken);

        return response()->json([
            'status'    => (bool)$result,
            'data'      => $result
        ]);
    }

    public function orderDetail($orderId = null, Request $request){
        $rememberToken  = $request->get('remember_token');
        $result         = $this->order_service->findDetail($rememberToken, $orderId);
        $packages       = $this->order_service->_allPackage();
        if(!empty($packages[($result->package_id - 1)]) AND $result){
            $package    = $packages[($result->package_id - 1)];
        }else{
            $package    = $this->order_service->getOldPackage($result->package);
        }
        $price      = $this->order_service->_calculateGrandTotal($result, $package);

        return response()->json([
            'status'    => (bool)$result,
            'data'      => $result,
            'package'   => $package,
            'price'     => $price
        ]);
    }

    public function validatePhone(Request $request){
        $phone      = $request->get('phone');
        $full_name  = $request->get('full_name');
        $rememberToken  = $request->get('remember_token');
        $result         = $this->phone_validate->standardPhone($phone);
        $needActivation = $this->order_service->needForValidateUserPhone($full_name, $result, $rememberToken);

        return response()->json([
            'status'    => (bool)$result,
            'data'      => $result,
            'activation' => $needActivation
        ]);
    }

    public function resendActivationCode(Request $request){
        $phone      = $request->get('phone');
        $result     = $this->phone_validate->standardPhone($phone);
        $needActivation = $this->order_service->resendActivationCode($phone);

        return response()->json([
            'status'    => (bool)$result,
            'data'      => $result,
            'activation' => $needActivation
        ]);
    }

    public function submitValidationCode(Request $request){
        $phone      = $request->get('phone');
        $code       = $request->get('activate_code');
        $result     = $this->order_service->submitValidationCode($phone,$code);

        return response()->json([
            'status'    => (bool)$result['status'],
            'data'      => $result['message'],
            'remember_token'    => ($result['status']) ? $result['user']->remember_token : ''
        ]);
    }

    public function getPackages(Request $request){
        $token      = $request->get('remember_token');
        $packages   = $this->order_service->getPackages($token);

        return response()->json([
            'status'    => true,
            'data'      => $packages
        ]);
    }

    public function findValidatePackage(Request $request){
        $packageId      = $request->get('package');
        $rememberToken  = $request->get('remember_token');
        $result         = $this->order_service->findAndValidatePackage($rememberToken, $packageId);

        return response()->json([
            'status'    => $result['status'],
            'data'      => $result['message']
        ]);
    }

    public function rejectOrder(Request $request){
        $pass       = $request->get('password');
        $orderId    = $request->get('order_id');
        $order      = null;
        if($pass == 22){
            $order  = $this->order_service->rejectOrder($orderId);
        }

        return response()->json([
            'status'    => $order
        ]);
    }

    public function courierPickedUp(Request $request){
        $pass       = $request->get('password');
        $orderId    = $request->get('order_id');
        $weight     = $request->get('weight');
        $count      = $request->get('count');
        $order      = null;
        if($pass == 22 && $weight && $count){
            $order  = $this->order_service->pickupSuccess($orderId, $weight, $count);
        }

        return response()->json([
            'status'    => $order
        ]);
    }

    public function checkForUpdate(Request $request){
        $version    = $request->get('version');
        $status     = false;
        if($version != '0.0.7'){
            $status     = true;
        }

        return response()->json([
            'status'    => $status,
            'data'      => ''
        ]);
    }

    public function findUser(Request $request){
        $q      = $request->get('q');
        $users  = $this->user->getUserAjax($q);

        return response()->json($users);
    }

    public function getLastOrder(Request $request){
        $userId = $request->get('user_id');
        $order  = $this->order_service->findLastOrderByUserId($userId);

        return response()->json($order);
    }

    public function test(){
        dd(date("Y-m-d H:i:s"));
    }
}