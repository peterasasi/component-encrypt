<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-10-24
 * Time: 9:50
 */

namespace by\component\encrypt\rsa;


use by\component\encrypt\exception\CryptException;

class Rsa
{

    /**
     * 生成RSA的公钥和私钥，返回数组第一位为公钥、第二位为私钥
     * @param string $digestAlg
     * @param int $bit 位数默认2048位 越多字符串长度越长
     * @param int $keyType
     * @return array
     */
    public static function generateRsaKeys($digestAlg = 'sha512', $bit = 2048, $keyType = OPENSSL_KEYTYPE_RSA) {
        $config = array(
            "digest_alg" => $digestAlg,
            "private_key_bits" => $bit,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);

        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey["key"];
        return [$pubKey, $privKey];
    }

    /**
     * 移除 公钥中的 换行符号，开头 结束字符
     * 变为一行字符串
     * @param $publicKey
     * @return mixed
     */
    public static function removeFormatPublicText($publicKey) {
        $publicKey = str_replace('-----BEGIN PUBLIC KEY-----', '', $publicKey);
        $publicKey = str_replace("\n", "", $publicKey);
        return str_replace('-----END PUBLIC KEY-----', '', $publicKey);
    }


    /**
     * 移除 私钥中的 换行符号，开头 结束字符
     * 变为一行字符串
     * @param $privateKey
     * @return string
     */
    public static function removeFormatPrivateText($privateKey) {
        $privateKey = str_replace('-----BEGIN PRIVATE KEY-----', '', $privateKey);
        $privateKey = str_replace("\n", "", $privateKey);
        return str_replace('-----END PRIVATE KEY-----', '', $privateKey);
    }

    /**
     *
     * 字符串中不得出现换行和BEGIN
     * 把纯字符串的转化为PEM格式的公钥
     * @param $key
     * @return string
     */
    public static function formatPublicText($key) {
        $newKey = "-----BEGIN PUBLIC KEY-----\n";
        $keyLength = strlen($key);
        $i = 0;
        while ($i < $keyLength) {
            $str64 = substr($key, $i, 64);
            $newKey .= $str64 . "\n";
            $i += 64;
        }
        $newKey .= '-----END PUBLIC KEY-----';
        return $newKey;
    }

    /**
     * 字符串中不得出现换行和BEGIN
     * 把纯字符串的转化为PEM格式的私钥
     * @param $key
     * @return string
     */
    public static function formatPrivateText($key) {
//        $newKey = "-----BEGIN RSA PRIVATE KEY-----\n";
//        $keyLength = strlen($key);
//        $i = 0;
//        while ($i < $keyLength) {
//            $str64 = substr($key, $i, 64);
//            $newKey .= $str64."\n";
//            $i += 64;
//        }
//        $newKey .= "-----END RSA PRIVATE KEY-----";
        return "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($key, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
    }

    public static function sign($data, $res) {
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        return base64_encode($sign);
    }

    public static function verifySign($data, $base64Sign, $key) {
        return openssl_verify($data, base64_decode($base64Sign), $key, OPENSSL_ALGO_SHA256);
    }

    /**
     * 公钥加密
     * @param $data
     * @param $public_key
     * @return string
     * @throws CryptException
     */
    public static function encryptPublicKey($data, $public_key)
    {
        $pi_key = openssl_pkey_get_public($public_key);
        if ($pi_key === false) {
            throw  new CryptException("public key read fail");
        }
        $ret = openssl_public_encrypt($data, $encryptData, $pi_key);
        openssl_free_key($pi_key);
        if ($ret == false) {
            throw  new CryptException("public encrypt fail".openssl_error_string());
        }

        return base64_encode($encryptData);
    }

    /**
     * 公钥解密
     * @param $data
     * @param $public_key
     * @return string
     * @throws CryptException
     */
    public static function decryptByPublicKey($data, $public_key)
    {

        $pi_key = openssl_pkey_get_public($public_key);
        if ($pi_key === false) {
            throw  new CryptException("public key read fail");
        }

        $decryptData = "";
        $ret = openssl_public_decrypt(base64_decode($data), $decryptData, $pi_key);

        openssl_free_key($pi_key);
        if ($ret == false) {
            throw  new CryptException("public decrypt fail");
        }

        return ($decryptData);
    }

    /**
     * 私钥加密
     * @param $data
     * @param $private_key
     * @return string  返回的是base64加密过的
     * @throws CryptException
     */
    public static function encrypt($data, $private_key)
    {
        $pi_key = openssl_pkey_get_private($private_key);
        if ($pi_key === false) {
            throw  new CryptException("private key read fail");
        }

        $encrypt = "";
        $ret = openssl_private_encrypt($data, $encrypt, $private_key);

        openssl_free_key($pi_key);
        if ($ret == false) {
            throw  new CryptException("private encrypt fail");
        }

        return base64_encode($encrypt);
    }

    /**
     * 私钥解密
     * @param $data
     * @param $private_key
     * @return string
     * @throws CryptException
     */
    public static function decrypt($data, $private_key)
    {
        $pi_key = openssl_pkey_get_private($private_key);

        if ($pi_key === false) {
            throw  new CryptException("private key get fail");
        }

        $decrypt = "";
        $ret = openssl_private_decrypt(base64_decode($data), $decrypt, $private_key);

        openssl_free_key($pi_key);
        if ($ret == false) {
            throw  new CryptException("private key decrypt fail");
        }

        return $decrypt;
    }


    /**
     * 公钥加密每次部分
     * @param $originalData
     * @param $publicKey
     * @return string base64格式
     */
    public static function encryptChunk($originalData, $publicKey)
    {
        $crypto = '';
        $chunk = '';
        foreach (str_split($originalData, 245) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $publicKey);
            $crypto .= $encryptData;
        }
        return base64_encode($crypto);
    }

    /**
     * 私钥分部解密
     * @param $encryptData
     * @param $privateKey
     * @return string
     */
    public static function decryptChunk($encryptData, $privateKey)
    {
        $crypto = '';
        $chunk = '';
        foreach (str_split(base64_decode($encryptData), 256) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $privateKey);
            $crypto .= $decryptData;
        }
        return $crypto;
    }
}
