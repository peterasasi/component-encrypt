<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    asasi<>
 * @copyright 2017 www.asasi.com Boye Inc. All rights reserved.
 * @link      http://www.asasi.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 16:32
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\encrypt\md5v3;


use by\component\encrypt\exception\CryptException;
use by\infrastructure\base\BaseEntity;

class DataStructEntity extends BaseEntity
{

    private $projectId;
    private $notifyId;
    private $clientSecret;
    private $clientId;
    private $data;
    private $time;
    private $type;
    private $sign;
    private $apiVer;
    private $appType;
    private $appVersion;

    public function __construct()
    {
        parent::__construct();
        $this->setApiVer(100);
        $this->setClientId('');
        $this->setClientSecret('');
        $this->setData('');
        $this->setTime('');
        $this->setType('');
        $this->setSign('');
        $this->setAppType("");
        $this->setAppVersion('');
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
     * @throws CryptException
     */
    public function isValid()
    {
        // 1. 参数是否缺失
        if (empty($this->getTime())) {
            throw new CryptException("time  is empty");
        }

        if (empty($this->getType())) {
            throw new CryptException("type  is empty");
        }

        if (empty($this->getNotifyId())) {
            throw new CryptException("notify_id  is empty");
        }

        if (empty($this->getClientSecret())) {
            throw new CryptException("client_secret is empty");
        }

        if (empty($this->getData())) {
            throw new CryptException("data  is empty");
        }

        // 2. 时间戳验证
        // 有效防止过期请求被重复使用
//        $now = microtime(true);//time();
//        //时间误差 +- 1分钟
//        if($now - 60 > $this->alParams->getTime() || $this->alParams->getTime() > $now + 60){
//        }
        $innerData = $this->getData();
        $sign = SignHelper::sign($this->getTime(), $this->getType(), $innerData, $this->getClientSecret(), $this->getNotifyId());

        if (!($sign == $this->getSign())) {
            throw new CryptException('sign verify failed');
        }
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * 获取id
     * @return string
     */
    public function getNotifyId()
    {
        return $this->notifyId;
    }

    /**
     * 设置id
     * @param string $notifyId
     */
    public function setNotifyId($notifyId)
    {
        $this->notifyId = $notifyId;
    }

    /**
     * 获取应用密钥
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * 设置应用密钥
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * 获取携带数据
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置携带数据
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
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

    public function toArray()
    {
        $data = parent::toArray();
        unset($data['create_time']);
        unset($data['update_time']);
        return $data;
    }

    /**
     * 获取项目id
     * @return integer
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * 设置项目id
     * @param integer $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
     * 获取应用id
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * 设置应用id
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getApiVer()
    {
        return $this->apiVer;
    }

    /**
     * @param mixed $apiVer
     */
    public function setApiVer($apiVer)
    {
        $this->apiVer = $apiVer;
    }
}
