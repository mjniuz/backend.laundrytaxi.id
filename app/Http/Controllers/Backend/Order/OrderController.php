<?php

namespace App\Http\Controllers\Backend\Order;

use App\Merchant\MerchantRepository;
use App\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    protected $order,$merchant;

    public function __construct(OrderService $order, MerchantRepository $merchant) {
        $this->order    = $order;
        $this->merchant = $merchant;
    }

    public function index(Request $request){
        $filters    = $request->all();
        $orders     = $this->order->get($filters);

        return view('backend.order.index', compact('orders','filters'));
    }

    public function detail($id = null){
        $merchants  = $this->merchant->getAll();
        $order      = $this->order->find($id);
        $package    = $this->order->_allPackage();
        $package    = !empty($order->package_id) ? $package[($order->package_id - 1)] : 0;
        $details    = $this->order->generateOrderDetail($order, $package);
        //dd($details);


        return view('backend.order.detail', compact('order', 'package', 'details','merchants'));
    }

    public function updateForm($id = null, Request $request){
        $order      = $this->order->find($id);
        $status     = $request->get('status');

        return view('backend.order.update-form', compact('order', 'status'));
    }

    public function updatePost($id = null, Request $request){
        $inputs = $request->all();
        $order  = $this->order->updateStatusOrder($id,$inputs);

        alertNotify((boolean)$order, "Updated", $request);

        return redirect(url('backend/order/detail/' . $id));
    }

    public function rejectForm($id = null, Request $request){
        $order  = $this->order->find($id);

        return view('backend.order.reject', compact('order'));
    }

    public function rejectSave($id = null, Request $request){
        $reason     = $request->get('message');
        $order      = $this->order->rejectOrder($id,$reason);

        alertNotify((boolean)$order, "Updated", $request);

        return redirect(url('backend/order/detail/' . $id));
    }

    public function createForm(Request $request){
        $packages   = $this->order->_allPackage();
        $merchants  = $this->merchant->getAll();

        return view('backend.order.form', compact('order', 'packages','merchants'));
    }

    public function create(Request $request){
        $inputs     = json_decode(json_encode($request->all()));
        $order      = $this->order->makeOrder($inputs);
        if($order){
            alertNotify(true, "Success", $request);
        }

        return redirect(url('backend/order/detail/' . $order->id));
    }

    public function customSmsForm($id = null, Request $request){
        $order  = $this->order->find($id);
        $sms    = $this->order->getSms($id);

        return view('backend.order.sms-custom', compact('order','sms'));
    }

    public function customSmsSave($id = null, Request $request){
        $message    = $request->get('message');
        $order      = $this->order->customSms($id, $message);

        alertNotify((boolean)$order, "Sms sent", $request);

        return redirect(url('backend/order/detail/' . $id));
    }

    public function assignMerchant($orderId, Request $request){
        $merchantId = $request->get('merchant_id');
        $result     = $this->order->assignMerchant($orderId,$merchantId);

        alertNotify($result['status'], $result['message'], $request);

        return redirect(url('backend/order/detail/' . $orderId));
    }
}
