<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(){
    }

    public function index(){
        return view('backend.dashboard');
    }

    public function totalSeminar(){
    	//return $this->dashboard->countTotalSeminar();
    }

    public function participant(){
    	//return $this->dashboard->countParticipant();
    }

    public function totalShipping(){
    	//return $this->dashboard->countTotalShipping();
    }

    public function totalPageFinance(){
    	//return $this->dashboard->countTotalPageFinance();
    }

    public function unprocessedSupplier(){
        //return $this->dashboard->countUnprocessedSupplierShipping();
    }

    public function chat(){
    	//return $this->dashboard->getChat();
    }
}