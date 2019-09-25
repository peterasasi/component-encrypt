<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    asasi<>
 * @copyright 2017 www.asasi.com Boye Inc. All rights reserved.
 * @link      http://www.asasi.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 16:44
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\encrypt\md5v3;


use by\component\encrypt\exception\CryptException;
use by\component\encrypt\interfaces\TransportInterface;
use by\infrastructure\helper\Object2DataArrayHelper;
use by\infrastructure\interfaces\ObjectToArrayInterface;

class Md5V3Transport implements TransportInterface
{

    private $clientSecret;

    /**
     * @var DataStructEntity
     */
    private $entity;

    private $data;

    /**
     * Md5V3Transport constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->entity = new DataStructEntity();
    }

    /**
     * 这种格式输入参数的数据返回不会完全加密 ['code'=>-1, 'data'=>[], 'msg'=>]
     * 不会完全加密的数据就无法通过decrypt进行解密
     * 其它格式的数据将会进行完全加密
     * @param array $data
     * @return array
     * @throws CryptException
     */
    function encrypt($data)
    {
        //
        if (array_key_exists('code', $data) &&
            array_key_exists('data', $data) &&
            array_key_exists('msg', $data)
        ) {

            return $this->returnPrimaryData($data);
        }

        return $this->completeEncryptData($data);
    }

    /**
     * 返回的数据没有加密只有签名
     * @param $data
     * @return array
     * @throws CryptException
     */
    private function returnPrimaryData($data)
    {
        $data['data'] = $this->toStringData($data['data']);

        $this->checkNullData($data['data']);

        $type = ($data['code'] == 0) ? "T" : "F";
        $data = $this->encryptInnerData($data);
        $entity = new DataStructEntity();
        $entity->setClientId($this->entity->getClientId());
        $returnData = [
            'client_id' => $this->entity->getClientId(),
            'time' => strval(time()),
            'data' => $data,
            'notify_id' => $this->entity->getNotifyId(),
            'type' => $type,
            'api_ver' => $this->entity->getApiVer()
        ];
        $returnData['sign'] = SignHelper::sign($returnData['time'], $returnData['type'], $returnData['data'], $this->entity->getClientSecret(), $returnData['notify_id']);

        return $returnData;
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

    public function encryptInnerData($data)
    {
        $str = json_encode($data, 0, 512);
        return base64_encode(base64_encode($str));
    }

    private function completeEncryptData($data)
    {
        $entity = new DataStructEntity();
        Object2DataArrayHelper::setData($entity, $data);
        $innerData = $this->encryptInnerData($entity->getData());
        $entity->setData($innerData);
        $sign = SignHelper::sign($entity->getTime(), $entity->getType(), $innerData, $entity->getClientSecret(), $entity->getNotifyId());
        $entity->setSign($sign);

        $arr = $entity->toArray();

        unset($arr['id']);
        unset($arr['client_secret']);

        $returnData = [
            'client_id' => $entity->getClientId(),
            'asasi' => $this->encryptTransmissionData($arr, $entity->getClientSecret()),
            'app_type' => $entity->getAppType(),
            'app_version' => $entity->getAppVersion()
        ];

        return $returnData;
    }

    private function encryptTransmissionData($param, $desKey)
    {
        $data = openssl_encrypt(json_encode($param), "des-ecb", $desKey);
        return base64_encode($data);
    }

    /**
     * @param $data
     * @return array
     * @throws CryptException
     */
    public function decrypt($data)
    {
        $this->data = $data;
        if (!array_key_exists('asasi', $this->data)) {
            throw new CryptException('param asasi need');
        }

        if (!array_key_exists('client_secret', $this->data)) {
            throw new CryptException('param client_secret need');
        }

        $this->clientSecret = $data['client_secret'];
        $asasi = $this->data['asasi'];
        unset($this->data['asasi']);
        $otherParams = $this->data;

        // 读取传输过来的加密参数
        $decodeData = $this->decryptTransmissionData($asasi, $this->clientSecret);
        $decodeData = $this->filter_post($decodeData);
        $obj = json_decode($decodeData, JSON_OBJECT_AS_ARRAY);
        $decodeData = empty($obj) ? [] : $obj;
        Object2DataArrayHelper::setData($this->entity, $decodeData);

        $this->entity->setClientSecret($this->clientSecret);
        $this->entity->isValid();

        $data = $this->decryptInnerData($this->entity->getData());
        if (empty($data)) $data = [];
        $requestStructData = $this->entity->toArray();
        unset($requestStructData['data']);

        // 增加前缀，免得 data 数组中同样的参数覆盖掉了
        foreach ($requestStructData as $key => $vo) {
            $otherParams['by_' . $key] = $vo;
            unset($otherParams[$key]);
        }

        return array_merge($otherParams, $data);
    }

    private function decryptTransmissionData($data, $key)
    {
        $data = openssl_decrypt(base64_decode($data), "des-ecb", $key);

        return ($data);
    }

    protected function filter_post($post)
    {
        $post = trim($post);
        for ($i = strlen($post) - 1; $i >= 0; $i--) {
            $ord = ord($post[$i]);
            if ($ord > 31 && $ord != 127) {
                $post = substr($post, 0, $i + 1);
                return $post;
            }
        }
        return $post;
    }

    public function decryptInnerData($data)
    {
        return json_decode(base64_decode(base64_decode($data)), JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @return DataStructEntity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param DataStructEntity $entity
     * rainbow 2017-12-08 09:53:58
     * dowm php del ':void'
     */
    public function setEntity(DataStructEntity $entity)
    {
        $this->entity = $entity;
    }

}
