<?php
namespace Home\Common\Common;

Class Tool{
    /**
     * 字母转大写
     */
    public static function getStrToMax($str)
    {
        $str = strtoupper($str);
        return $str;
    }
}