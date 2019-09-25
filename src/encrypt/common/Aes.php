<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace by\component\encrypt\common;


class Aes
{
    public static function encrypt($data, $key, $iv = '1234567890123456') {
        return openssl_encrypt($data,'aes-128-cbc',$key,false,$iv);
    }

    public static function decrypt($data, $key, $iv = '1234567890123456') {
        return openssl_decrypt($data,'aes-128-cbc',$key,false,$iv);
    }
}