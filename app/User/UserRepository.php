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
}