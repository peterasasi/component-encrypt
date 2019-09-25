<?php


namespace by\component\encrypt\http;


class RequestContext
{
    protected $url;

    protected $clientId;
    protected $clientSecret;
    protected $alg;
    protected $sysPublicKey;
    protected $userPrivateKey;

    protected $appType;
    protected $appVersion;
    protected $lang;

    /**
     * RequestContext constructor.
     * @param $lang
     */
    public function __construct()
    {
        $this->url = '';
        $this->lang = "zh-cn";
        $this->appType = 'server';
        $this->appVersion = '1.0.0';
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
    public function getAlg()
    {
        return $this->alg;
    }

    /**
     * @param mixed $alg
     */
    public function setAlg($alg)
    {
        $this->alg = $alg;
    }

    /**
     * @return mixed
     */
    public function getSysPublicKey()
    {
        return $this->sysPublicKey;
    }

    /**
     * @param mixed $sysPublicKey
     */
    public function setSysPublicKey($sysPublicKey)
    {
        $this->sysPublicKey = $sysPublicKey;
    }

    /**
     * @return mixed
     */
    public function getUserPrivateKey()
    {
        return $this->userPrivateKey;
    }

    /**
     * @param mixed $userPrivateKey
     */
    public function setUserPrivateKey($userPrivateKey)
    {
        $this->userPrivateKey = $userPrivateKey;
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
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param mixed $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }
}
