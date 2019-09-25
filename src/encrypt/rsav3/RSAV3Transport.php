<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | ©2018 California State Lottery All rights reserved.
// +----------------------------------------------------------------------
// | Author: Smith Jack
// +----------------------------------------------------------------------

namespace by\component\encrypt\rsav3;


use by\component\encrypt\interfaces\ClientTransportInterface;
use by\component\encrypt\interfaces\TransportInterface;
use by\component\encrypt\rsa\Rsa;

class RSAV3Transport implements TransportInterface,ClientTransportInterface
{
    private $notifyId;
    private $clientSecret;
    private $clientId;
    private $appRequestTime;
    private $serviceType;
    private $sign;
    private $serviceVersion;
    private $appType;
    private $appVersion;
    private $bussData;

    /**
     * RSAV2Transport constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        if (!extension_loaded("openssl")) {
            throw new \Exception("Please open the openssl extension first.");
        }
    }

    /**
     * 签名
     * @param $privateKey
     * @return string
     */
    public function sign($privateKey)
    {
        ksort($this->bussData, SORT_ASC);
        $bussData = json_encode($this->bussData, JSON_UNESCAPED_UNICODE);
        $data = $this->getAppRequestTime() . $this->getClientSecret() . $this->getServiceType() . $this->getServiceVersion() . $bussData;
        return Rsa::sign($data, $privateKey);
    }

    /**
     * @return mixed
     */
    public function getAppRequestTime()
    {
        return $this->appRequestTime;
    }

    /**
     * @param mixed $appRequestTime
     */
    public function setAppRequestTime($appRequestTime)
    {
        $this->appRequestTime = $appRequestTime;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return mixed
     */
    public function getServiceType()
    {
        return $this->serviceType;
    }

    /**
     * @param mixed $serviceType
     */
    public function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType;
    }

    /**
     * @return mixed
     */
    public function getServiceVersion()
    {
        return $this->serviceVersion;
    }

    /**
     * @param mixed $serviceVersion
     */
    public function setServiceVersion($serviceVersion)
    {
        $this->serviceVersion = $serviceVersion;
    }

    public function toArray()
    {
        return [
            'notify_id' => $this->getNotifyId(),
            'client_secret' => $this->getClientSecret(),
            'app_request_time' => $this->getAppRequestTime(),
            'service_type' => $this->getServiceType(),
            'service_version' => $this->getServiceVersion(),
            'app_type' => $this->getAppType(),
            'app_version' => $this->getAppVersion(),
            'buss_data' => $this->getBussData(),
            'client_id' => $this->getClientId(),
            'sign' => $this->getSign()
        ];
    }

    /**
     * @return mixed
     */
    public function getNotifyId()
    {
        return $this->notifyId;
    }

    /**
     * @param mixed $notifyId
     */
    public function setNotifyId($notifyId)
    {
        $this->notifyId = $notifyId;
    }

    /**
     * @return mixed
     */
    public function getAppType()
    {
        return $this->appType;
    }

    /**
     * @param mixed $appType
     */
    public function setAppType($appType)
    {
        $this->appType = $appType;
    }

    /**
     * @return mixed
     */
    public function getAppVersion()
    {
        return $this->appVersion;
    }

    /**
     * @param mixed $appVersion
     */
    public function setAppVersion($appVersion)
    {
        $this->appVersion = $appVersion;
    }

    /**
     * @return mixed
     */
    public function getBussData()
    {
        return $this->bussData;
    }

    /**
     * @param mixed $bussData
     */
    public function setBussData($bussData)
    {
        $this->bussData = $bussData;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * @param mixed $sign
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    }

    /**
     * 服务器端解密
     * @param $data
     * @return array|mixed
     * @throws \Exception
     */
    function decrypt($data)
    {
        $publicKey = $data['my_public_key'];
        $privateKey = $data['sys_private_key'];

        $clientSecret = $data['client_secret'];
        // 公钥解密
        $data['buss_data'] = Rsa::decryptChunk($data['buss_data'], ($privateKey));
        $data['buss_data'] = json_decode($data['buss_data'], JSON_OBJECT_AS_ARRAY);
        if (empty($data['buss_data'])) $data['buss_data'] = [];
        ksort($data['buss_data'], SORT_ASC);
        $this->setNotifyId($data['notify_id']);
        $this->setClientSecret($clientSecret);
        $this->setAppRequestTime($data['app_request_time']);
        $this->setServiceType($data['service_type']);
        $this->setServiceVersion($data['service_version']);
        $this->setAppType($data['app_type']);
        $this->setAppVersion($data['app_version']);
        $this->setBussData($data['buss_data']);
        $this->setClientId($data['client_id']);
        $this->setSign($data['sign']);
        if (!($this->verifySign($publicKey))) {
            throw new \Exception('[Decrypt]Sign Verify Failed');
        }
        $decrypt = $this->toArray();
        $decryptData = $decrypt['buss_data'];
        if (empty($decryptData)) $decryptData = [];

        unset($decrypt['client_secret']);
        unset($decrypt['buss_data']);
        unset($decrypt['sign']);

        return array_merge($decrypt, $decryptData);
    }



    function clientDecrypt($data)
    {
        $publicKey = $data['sys_public_key'];
        $privateKey = $data['my_private_key'];

        $clientSecret = $data['client_secret'];
        // 公钥解密
        $data['buss_data'] = Rsa::decryptChunk($data['buss_data'], ($privateKey));
        $data['buss_data'] = json_decode($data['buss_data'], JSON_OBJECT_AS_ARRAY);
        if (empty($data['buss_data'])) $data['buss_data'] = [];
        ksort($data['buss_data'], SORT_ASC);
        $this->setNotifyId($data['notify_id']);
        $this->setClientSecret($clientSecret);
        $this->setAppRequestTime($data['app_request_time']);
        $this->setServiceType($data['service_type']);
        $this->setServiceVersion($data['service_version']);
        $this->setAppType($data['app_type']);
        $this->setAppVersion($data['app_version']);
        $this->setBussData($data['buss_data']);
        $this->setClientId($data['client_id']);
        $this->setSign($data['sign']);
        if (!($this->verifySign($publicKey))) {
            throw new \Exception('[Decrypt]Sign Verify Failed');
        }
        $decrypt = $this->toArray();
        $decryptData = $decrypt['buss_data'];
        if (empty($decryptData)) $decryptData = [];

        unset($decrypt['client_secret']);
        unset($decrypt['buss_data']);
        unset($decrypt['sign']);

        return array_merge($decrypt, $decryptData);
    }


    function clientEncrypt($data)
    {
        $publicKey = $data['sys_public_key'];
        $privateKey = $data['my_private_key'];
        ksort($data['buss_data'], SORT_ASC);

        $this->setNotifyId($data['notify_id']);
        $this->setClientSecret($data['client_secret']);
        $this->setAppRequestTime($data['app_request_time']);
        $this->setServiceType($data['service_type']);
        $this->setServiceVersion($data['service_version']);
        $this->setAppType($data['app_type']);
        $this->setAppVersion($data['app_version']);
        $this->setBussData($data['buss_data']);
        $this->setClientId($data['client_id']);
        $this->setSign($this->sign($privateKey));
        $arr = $this->toArray();
        unset($arr['client_secret']);
        $arr['buss_data'] = json_encode($arr['buss_data'], JSON_UNESCAPED_UNICODE);
        $arr['buss_data'] = Rsa::encryptChunk($arr['buss_data'], ($publicKey));
        return $arr;
    }

    /**
     * 服务器端加密
     * @param $data
     * @return mixed|string
     */
    function encrypt($data)
    {
        $publicKey = $data['my_public_key'];
        $privateKey = $data['sys_private_key'];
        ksort($data['buss_data'], SORT_ASC);

        $this->setNotifyId($data['notify_id']);
        $this->setClientSecret($data['client_secret']);
        $this->setAppRequestTime($data['app_request_time']);
        $this->setServiceType($data['service_type']);
        $this->setServiceVersion($data['service_version']);
        $this->setAppType($data['app_type']);
        $this->setAppVersion($data['app_version']);
        $this->setBussData($data['buss_data']);
        $this->setClientId($data['client_id']);
        $this->setSign($this->sign($privateKey));
        $arr = $this->toArray();
        unset($arr['client_secret']);
        $arr['buss_data'] = json_encode($arr['buss_data'], JSON_UNESCAPED_UNICODE);
        $arr['buss_data'] = Rsa::encryptChunk($arr['buss_data'], $publicKey);
        return $arr;
    }

    /**
     * 验签
     * @param $publicKey
     * @return bool
     */
    public function verifySign($publicKey)
    {
        $data = $this->getAppRequestTime() . $this->getClientSecret() . $this->getServiceType() . $this->getServiceVersion() . json_encode($this->getBussData(), JSON_UNESCAPED_UNICODE);
        return Rsa::verifySign($data, $this->sign, $publicKey);
    }

    function encryptInnerData($data)
    {
        return $data;
    }


    function decryptInnerData($data)
    {
        return $data;
    }


}
