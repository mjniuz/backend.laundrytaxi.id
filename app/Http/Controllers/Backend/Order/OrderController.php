<?php

namespace App\Http\Controllers\Backend\Order;

use App\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
    protected $order;

    public function __construct(OrderService $order) {
        $this->order    = $order;
    }

    public function index(Request $request){
        $filters    = $request->all();
        $orders     = $this->order->get($filters);

        return view('backend.order.index', compact('orders','filters'));
    }

    public function detail($id = null){
        $order      = $this->order->find($id);
        $package    = $this->order->_allPackage();
        $package    = !empty($order->package_id) ? $package[($order->package_id - 1)] : 0;
        $details    = $this->order->generateOrderDetail($order, $package);
        //dd($details);


        return view('backend.order.detail', compact('order', 'package', 'details'));
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
        return view('backend.order.form', compact('order', 'packages'));
    }

    public function create(Request $request){
        $inputs     = json_decode(json_encode($request->all()));
        $order      = $this->order->makeOrder($inputs);
        if($order){
            alertNotify(true, "Success", $request);
        }

        return redirect(url('backend/order/detail/' . $order->id));
    }
}
