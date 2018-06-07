<?php

namespace App\Http\Controllers\Backend\User;

use App\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $user) {
        $this->user = $user;
    }

    public function index(Request $request){
        $filters    = $request->all();
        $users      = $this->user->get($filters);

        return view('backend.user.index', compact('users','filters'));
    }

    public function detail($id = null){
        $user   = $this->user->find($id);

        return view('backend.user.detail', compact('user'));
    }
}
