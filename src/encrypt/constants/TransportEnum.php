<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-15
 * Time: 9:41
 */

namespace by\component\encrypt\constants;

/**
 * 算法类型
 * Class AlgEnum
 * @package by\component\encrypt\algorithm
 */
class TransportEnum
{
    const _EMPTY = "empty";
    /**
     * md5 version 1 传输算法
     * des    作为传输数据加解密
     * md5    作为签名验证
     * base64 作为传输真实内容数据加解密
     */
    const MD5_V1 = "md5";

    /**
     * md5 version 2 传输算法 | 差别在于 des 加密 对java处理的时候增加了padding
     * des    作为传输数据加解密
     * md5    作为签名验证
     * base64 作为传输真实内容数据加解密
     */
    const MD5_V2 = "md5_v2";
    /**
     * md5 version 3 传输算法 | 差别在于 des 加密 使用了openssl
     * des    作为传输数据加解密
     * md5    作为签名验证
     * base64 作为传输真实内容数据加解密
     */
    const MD5_V3 = "md5_v3";
    /**
     * md5 version 4 传输算法  较简单 只进行了签名验证
     * des    作为传输数据加解密
     * md5    作为签名验证
     * base64 作为传输真实内容数据加解密
     */
    const MD5_V4 = "md5_v4";

    /**
     * RSA_V1 RSA_V2是一样的 传输算法
     *
     * md5 作为签名验证
     * rsa 作为传输数据加解密
     **/
    const RSA_V1 = "rsa_v1";
    /**
     * RSA_V1 RSA_V2是一样的 传输算法
     *
     * rsa 作为签名验证
     **/
    const RSA_V2 = "rsa_v2";
    /**
     * RSA_V2是一样的签名基础上增加了RSA sha256数据加密
     *
     * rsa 作为传输数据加解密
     **/
    const RSA_V3 = "rsa_v3";

    /**
     * 不做任何处理
     */
    const Nothing = "nothing";
}
