<?php
/**
 * Created by PhpStorm.
 * User: asasi
 * Date: 2018/7/5
 * Time: 10:33
 */

namespace by\component\encrypt\nothing;


use by\component\encrypt\exception\CryptException;
use by\component\encrypt\interfaces\ClientTransportInterface;
use by\component\encrypt\interfaces\TransportInterface;
use by\infrastructure\interfaces\ObjectToArrayInterface;

class NothingTransport implements TransportInterface, ClientTransportInterface
{

    public function __construct($data = [])
    {

    }

    function clientDecrypt($data)
    {
        return $data;
    }

    function clientEncrypt($data)
    {
        $data = $this->toStringData($data);
        return $data;
    }


    function encrypt($data)
    {
        $data = $this->toStringData($data);
        return $data;
    }

    private function toStringData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                $data[$key] = $this->toStringData($value);
            }
        } elseif (!is_object($data) && !is_string($data)) {
            return strval($data);
        } elseif (is_object($data)) {
            if ($data instanceof ObjectToArrayInterface) {
                return $data->toArray();
            }
        }

        return $data;
    }

    function encryptInnerData($data)
    {
        return null;
    }

    function decrypt($data)
    {

        $keys = ['time', 'notify_id', 'api_ver', 'type'];

        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $data['by_'.$key] = $data[$key];
                unset($data[$key]);
            }
        }

        return $data;
    }

    function decryptInnerData($data)
    {
        return null;
    }

    /**
     * @param $data
     * @throws CryptException
     */
    protected function checkNullData($data)
    {
        if (is_null($data)) {
            throw new CryptException(('err_return_is_not_null'));
        } elseif (is_array($data)) {
            foreach ($data as $value) {
                self::checkNullData($value);
            }
        } elseif (is_object($data) && method_exists($data, "toArray")) {
            foreach ($data->toArray() as $key => $value) {
                self::checkNullData($value);
            }
        }
    }

}
