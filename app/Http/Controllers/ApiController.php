<?php

namespace App\Http\Controllers;

use App\Order\OrderService;
use App\User\PhoneNumberService;
use Illuminate\Http\Request;

class ApiController extends Controller{
    protected $order_service, $phone_validate;
    public function __construct(OrderService $orderService, PhoneNumberService $phoneValidate) {
        $this->order_service    = $orderService;
        $this->phone_validate   = $phoneValidate;
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

        return response()->json([
            'status'    => (bool)$result,
            'data'      => $result
        ]);
    }

    public function validatePhone(Request $request){
        $phone      = $request->get('phone');
        $result     = $this->phone_validate->standardPhone($phone);

        return response()->json([
            'status'    => (bool)$result,
            'data'      => $result
        ]);
    }
}