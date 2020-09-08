<?php

namespace App\Helpers;

class FunctionHelper
{
    public static function getBrCodeFromAccountNumber($accountNumber)
    {
        if(empty($accountNumber)){
            return null;
        }

        $brCode = substr($accountNumber, 0, 3);
        return $brCode;
    }
}
