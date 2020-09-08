<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function is_array_empty($arr){
        if(is_array($arr)){
            foreach($arr as $value){
                if(!empty($value)){
                    return false;
                }
            }
        }
        return true;
    }
}
