<?php


namespace by\component\encrypt\http;


use by\component\encrypt\factory\TransportFactory;
use by\component\encrypt\interfaces\ClientTransportInterface;
use by\component\encrypt\rsa\Rsa;
use by\component\exception\InvalidArgumentException;
use by\component\http\HttpRequest;
use by\infrastructure\base\CallResult;
use by\infrastructure\helper\CallResultHelper;
use Exception;

abstract class BaseHttp
{
    protected $http;
    /**
     * @var RequestContext
     */
    protected $context;


    protected $supportAlg = ['rsa_v3', 'md5_v4', 'nothing'];

    public function __construct(RequestContext $context)
    {
        $this->http = new HttpRequest();
        $this->context = $context;
        if (!in_array($context->getAlg(), $this->supportAlg)) throw new InvalidArgumentException('not support alg '.$context->getAlg());
    }

    /**
     * 发起POST请求
     * @param string $serviceType
     * @param array $bussData
     * @param string $url 请求地址 如果为空，则使用RequestContext的地址
     * @param string $serviceVersion
     * @return CallResult
     * @throws Exception
     */
    public function doPostRequest($serviceType, $bussData, $url = '', $serviceVersion = '100')
    {
        $transport = TransportFactory::getAlg($this->context->getAlg(), []);
        if (!$transport instanceof ClientTransportInterface) {
            throw new InvalidArgumentException('error alg');
        }
        if (empty($bussData)) {
            $bussData = ['t' => '1'];
        }
        if (empty($url)) $url = $this->context->getUrl();

        $now = time();
        $data = [
            'notify_id' => $now,
            'client_secret' => $this->context->getClientSecret(),
            'app_request_time' => $now,
            'service_type' => $serviceType,
            'service_version' => $serviceVersion,
            'app_type' => $this->context->getAppType(),
            'app_version' => $this->context->getAppVersion(),
            'buss_data' => $bussData,
            'client_id' => $this->context->getClientId(),
        ];

        if (!empty($this->context->getSysPublicKey()) && !empty($this->context->getUserPrivateKey())) {
            $data['sys_public_key'] = Rsa::formatPublicText($this->context->getSysPublicKey());
            $data['my_private_key'] = Rsa::formatPrivateText($this->context->getUserPrivateKey());
        }

        // 加密
        $encryptData = $transport->clientEncrypt($data);

        $resp = $this->http
            ->header('Origin', 'ByHttp')
            ->ua('ByHttp')
            // 15秒超时
            ->timeout(15000, 10000)
            ->post($url, $encryptData);
        if ($resp->success) {
            $content = $resp->getBody()->getContents();
            $json = json_decode($content, JSON_OBJECT_AS_ARRAY);
            if (is_null($json)) {
                return CallResultHelper::fail($content);
            }
            if (is_array($json)) {
                if (array_key_exists('code', $json) && array_key_exists('msg', $json) && array_key_exists('data', $json)) {
                    return new CallResult($json['data'], $json['msg'], $json['code']);
                }
            }
            return CallResultHelper::fail($resp->getBody()->getContents());
        } else {
            return CallResultHelper::fail($resp->error());
        }
    }
}
