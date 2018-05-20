<?php

namespace App\User;


class PhoneNumberService
{
    public function standardPhone($phone){
        $phoneUtil  = \libphonenumber\PhoneNumberUtil::getInstance();
        try{
            $parsePhone = $phoneUtil->parse($phone, "ID");
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }

        $isValid    = $phoneUtil->isValidNumber($parsePhone);

        if(!$isValid){
            return false;
        }

        try {
            $correctPhone   = $phoneUtil->format($parsePhone, \libphonenumber\PhoneNumberFormat::E164);
        } catch (\libphonenumber\NumberParseException $e) {
            $correctPhone   = false;
        }

        return $correctPhone;
    }
}
