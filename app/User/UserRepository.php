<?php

namespace App\User;

class UserRepository{
    public function createUpdate($input){
        $phoneValid     = new PhoneNumberService();

        $phone  = $phoneValid->standardPhone($input->phone);
        $user   = $this->findByPhone($phone);
        if(!$user){
            $user   = new User();
            $user->remember_token   = $this->_generateToken();
            $user->password         = '';
        }

        $user->phone    = $phone;
        $user->name     = $input->full_name;
        $user->save();

        return $user;
    }

    public function findByPhone($phone = ''){
        $phoneValid     = new PhoneNumberService();
        $phone          = $phoneValid->standardPhone($phone);

        return User::with([])->where('phone',$phone)->first();
    }

    public function findByToken($token = ''){
        return User::with([])->where('remember_token',$token)->first();
    }

    private function _generateToken($length = 18){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz=-*&#@!/><';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createPhoneValidation($user){
        $user->activate_code    = rand(10000,99999);
        $user->activate_code_expired    = date("Y-m-d H:i:s",strtotime('+3 hours'));
        $user->save();

        return $user;
    }

    public function activateUser($user){
        $user->activated    = date("Y-m-d H:i:s");
        $user->save();

        return $user;
    }

    public function get($filters = []){
        $users  = User::with([]);

        if(!empty($filters['name'])){
            $users->where('name','like', $filters['name']);
        }

        if(!empty($filters['phone'])){
            $phoneValid     = new PhoneNumberService();

            $phone  = $phoneValid->standardPhone($filters['phone']);
            $users->where('phone','like', $phone);
        }

        return $users->paginate(25);
    }

    public function getUserAjax($query){
        return User::with([])->where(function ($q)use($query){
            $q->where('name', 'like', '%'. $query .'%')
                ->orWhere('phone','like','%'. $query .'%');
        })->get();
    }

    public function find($id){
        return User::with(['orders.merchant'])->find($id);
    }
}