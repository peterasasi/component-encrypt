<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 16/4/22
 * Time: 14:08
 */

namespace by\component\encrypt\des;

/**
 * DES
 * Class DesCrypt
 * @package by\vendor\Crypt
 */
class Des
{


    public static function encryptDesEcbPKCS5($input, $key)
    {
        $encode = openssl_encrypt($input, "des-ecb", $key);
        return $encode;
    }

    /**
     * 对明文信息进行加密
     * @param string $content 原文
     * @param string $key 8位密钥
     * @return string 二进制，返回最好用base64加密下
     * @internal param string $salt
     */
    static public function encode($content, $key)
    {
        $encode = openssl_encrypt($content, "des-ecb", $key);
        return $encode;
    }

    /**
     * 对密文进行解密
     * @param string $encode_content
     * @param string $key  密钥
     * @return string
     * @internal param $content
     */
    static public function decode($encode_content, $key)
    {
        if (empty($encode_content)) {
            return "";
        }
        return trim(openssl_decrypt($encode_content, "des-ecb", $key));
    }


}
