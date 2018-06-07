<?php

namespace App\SmsGateway;

class ZenzivaService{
    protected $cs;
    public function __construct() {
        $this->cs   = "081283257709";
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

    public function customerOrderSMS($user){
        $sms = "Hi pelanggan LaundryTaxi ". $user->name .", Pesanan laundry kamu akan segera dijemput oleh merchant kami pada jam kerja, harap pastikan nomor kamu aktif terus yaa!!";
        $this->send($sms, $user->phone);
    }

    public function validationPhone($user){
        $sms = "Hi pelanggan LaundryTaxi ". $user->name .", verifikasi kode kamu adalah, " . $user->activate_code . ". Kode berlaku hingga " . $user->activate_code_expired;
        $this->send($sms, $user->phone);
    }

    public function merchantOrderSMS($merchant, $user){
        $sms = "Hi merhcant LaundryTaxi, Ada pesanan laundry untuk kamu dari " . $user->name . ", ". $user->phone .", kamu bisa check di aplikasi kamu sekarang!!";
        $this->send($sms, $merchant->phone);
        $this->send($sms, '081806423887');
        $this->send($sms, '081283257709');
    }

    public function updateOrderPickup($user, $order){
        $sms    = "Hi pelanggan LaundryTaxi " . $user->name . ", laundry kamu dg no ". $order->invoice_no ." telah dipickup, total berat " . $order->actual_weight . "kg dan biaya Rp" .
        number_format($order->grand_total,0) . " akan segera diproses.";
        $this->send($sms, $user->phone);
    }

    public function rejectOrder($user, $order){
        $sms    = "Hii pelanggan LaundryTaxi " . $user->name .", mohon maaf, pesanan kamu dg no ". $order->invoice_no ." tdk dpt kami teruskan krn ". $order->success_comment .". Kami akn terus memperbaiki layanan.";
        $this->send($sms, $user->phone);
    }

    public function courierPickedUp($user, $order){
        $sms    = "Hi pelanggan LaundryTaxi " . $user->name . ", laundry no ". $order->invoice_no ." telah dipickup oleh kurir dengan berat " . $order->actual_weight . "kg dan jumal qty " . $order->actual_count . "pcs, total ". number_format($order->grand_total,0) ." akan segera diproses. CS: " . $this->cs;
        $this->send($sms, $user->phone);
    }

    public function onProcess($user, $order, $deliveryDate = ''){
        $sms    = "Hi pelanggan LaundryTaxi " . $user->name . ", laundry no ". $order->invoice_no ." telah/sedang diproses dan akan diantarkan pada tanggal ". $deliveryDate .", total ". number_format($order->grand_total,0) ." . CS: " . $this->cs;
        $this->send($sms, $user->phone);
    }

    public function delivered($user, $order){
        $sms    = "Hi pelanggan LaundryTaxi " . $user->name . ", laundry no ". $order->invoice_no ." telah dikirim, jika ada masalah dengan laundry kamu, bisa hubungi CS no: " . $this->cs . ", Terimakasih silahkan order kembali!!";
        $this->send($sms, $user->phone);
    }
}