<?php

namespace App\Http\Controllers\Backend\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\adminUsers\adminUserRepository;
use App\Notifications\MailResetPasswordToken;

class LoginController extends Controller{
    use AuthenticatesUsers;

    protected $loginView = 'admin.auth.login';
    protected $guard = 'admin';
    protected $redirectTo = null;

	protected $adminUser;
	public function __construct(adminUserRepository $adminUser){
		$this->adminUser	= $adminUser;
	}

    public function index(){
        return view('backend.auth.login');
    }

    public function authenticate(Request $request){
        $email 		= $request->email;
        $password 	= $request->password;
        $adminUser	= $this->adminUser->adminLogin($email, $password);
        if(!$adminUser){
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', 'Invalid email or password');

            return redirect('backend/login');
        }

        return redirect('backend/dashboard');
    }

    public function logout(Request $request){
        $adminUser  = \Sentinel::check();


        $this->adminUser->adminLogout();

        return redirect('backend/login');
    }

    public function reset(){
        return view('backend.auth.reset');
    }


    public function resetPassword(Request $request){
        $email  = $request->get('email');
        $user   = $this->adminUser->findByEmail($email);
        $token  = $this->adminUser->generateTokenForgotPassword($user);

        if($token){

            $request->session()->flash('alert-class','success');
            $request->session()->flash('status', 'Forgot password success sent');
            $user->notify(new MailResetPasswordToken($token));
        }

        return redirect('backend/login');
    }

    public function resetToken($token = "", Request $request){
        $reset  = $this->adminUser->getUserByToken($token);
        if(!$reset){
            $request->session()->flash('alert-class','danger');
            $request->session()->flash('status', 'Token salah/expired');

            return redirect('backend/login');
        }
        return view('backend.auth.change-pass', compact('token', 'user'));
    }

    public function changePass($token = "", Request $request){
        $reset       = $this->adminUser->getUserByToken($token);
        $newPass    = $request->get('password');
        if(!$reset){
            $request->session()->flash('alert-class','error');
            $request->session()->flash('status', 'Token salah/expired');

            return redirect('backend/login');
        }

        $this->adminUser->changePassword($reset->email, $newPass);

        $request->session()->flash('alert-class','success');
        $request->session()->flash('status', 'Password changed');

        $user   = $this->adminUser->findByEmail($reset->email);
        $dataLog    = [
            "admin_user_id" => $user->id,
            "user_detail"   => $user->toArray(),
            "description"   => "Admin Change Password",
            "key"           => "admin_change_password",
            "value"         => "",
        ];

        return redirect('backend/login');
    }

}
