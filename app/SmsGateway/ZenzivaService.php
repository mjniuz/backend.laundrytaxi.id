<?php

namespace App\SmsGateway;

class ZenzivaService{
    public function __construct() {
    }

    public function send($message, $phone, $type = ''){
        $phone      = str_replace('+','',$phone);
        $username   = env('ZENZIVA_USER', false);
        $password   = env('ZENZIVA_PASS', false);
        $message    = str_replace(" ","%20", $message);


        $sms_command    = 'https://reguler.zenziva.net/apps/smsapi.php?userkey='. $username .'&passkey='. $password .'&nohp='. $phone .'&pesan=' . $message;

        $send   = null;
        try{
            $send = file_get_contents($sms_command);
        }catch (\Exception $e){
            return [
                'status'    => false,
                'code'      => $send,
                'message'   => 'SMS gagal dikirim'
            ];
        }
        $xml        = simplexml_load_string($send) or die("Error: Cannot create object");

        return [
            'status'    => false,
            'code'      => $send,
            'message'   => "ERROR : Username dan Password Anda salah atau credit habis."
        ];
    }

    public function customerOrderSMS($user, $phone){
        $sms = "Hi pelanggan LaundryTaxi". $user->name .", Pesanan laundry kamu akan segera dijemput oleh merchant kami, harap pastikan nomor kamu aktif terus yaa!!";
        $this->send($sms, $phone, $type = '');
    }

    public function merchantOrderSMS($user, $phone){
        $sms = "Hi merhcant LaundryTaxi, Ada pesanan laundry untuk kamu dari " . $user->name . ", kamu bisa check di aplikasi kamu sekarang!!";
        $this->send($sms, $phone, $type = '');
    }
}