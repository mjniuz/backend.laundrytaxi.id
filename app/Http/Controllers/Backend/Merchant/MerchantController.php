<?php

namespace App\Http\Controllers\Backend\Merchant;

use App\Merchant\MerchantRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MerchantController extends Controller
{
    protected $merchant;

    public function __construct(MerchantRepository $merchant) {
        $this->merchant = $merchant;
    }

    public function index(Request $request){
        $filters    = $request->all();
        $merchants  = $this->merchant->get($filters);

        return view('backend.merchant.index', compact('merchants','filters'));
    }

    public function detail($id = null){
        $merchant   = $this->merchant->find($id);

        return view('backend.merchant.detail', compact('merchant'));
    }

    public function form($id = null, Request $request){
        $merchant   = $this->merchant->find($id);

        return view('backend.merchant.form', compact('merchant'));
    }

    public function update($id = null, Request $request){
        $inputs     = $request->all();
        $result     = $this->merchant->createUpdate($id, $inputs);
        if(!$result){
            alertNotify(false, "Update error", $request);
            return back()->withInput();
        }

        alertNotify(true, "Update Success", $request);
        return redirect(url('backend/merchant/detail/' . $result->id));
    }

    public function addTransaction($merchantId = null, Request $request){
        $inputs = $request->all();
        $result = $this->merchant->addTransactionBalance($merchantId, $inputs);

        alertNotify($result['status'], $result['message'], $request);
        return back()->withInput();
    }

    public function balances(Request $request){
        $filters    = $request->all();
        $balances   = $this->merchant->getBalances($filters);

        $merchantId = !empty($filters['merchant_id']) ? $filters['merchant_id'] : null;
        $merchant   = $this->merchant->find($merchantId);

        return view('backend.merchant.balances', compact('merchant','balances'));
    }
}
