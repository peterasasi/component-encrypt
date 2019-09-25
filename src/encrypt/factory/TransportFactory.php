<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 2016-11-15
 * Time: 9:41
 */

namespace by\component\encrypt\factory;

use by\component\encrypt\constants\TransportEnum;
use by\component\encrypt\interfaces\TransportInterface;
use by\component\encrypt\md5v3\Md5V3Transport;
use by\component\encrypt\md5v4\Md5V4Transport;
use by\component\encrypt\nothing\NothingTransport;
use by\component\encrypt\rsav2\RSAV2Transport;
use by\component\encrypt\rsav3\RSAV3Transport;

/**
 * 传输算法工厂
 * Class TransportFactory
 * @package by\component\encrypt\algorithm
 */
class TransportFactory
{
    /**
     * 获取传输算法
     * @param string $enum
     * @param $data
     * @return bool|TransportInterface
     * @throws \Exception
     */
    public static function getAlg($enum, $data)
    {

        switch ($enum) {
            case TransportEnum::Nothing:
                return new NothingTransport($data);
            case TransportEnum::MD5_V3:
                return new Md5V3Transport($data);
            case TransportEnum::MD5_V4:
                return new Md5V4Transport($data);
            case TransportEnum::RSA_V3:
                return new RSAV3Transport($data);
            case TransportEnum::RSA_V1:
            case TransportEnum::RSA_V2:
                return new RSAV2Transport($data);
            default:
                return false;
        }
    }
}
