# RSA_V3 encrypt 说明

## 依赖

1. openssl

## 传输数据结构含义

|参数 | 值|说明|
|------------- |:-------------:| -----:|
|client_id|客户端标识|分配的id|
|app_version|客户端应用版本|应用app的版本，数字形式|
|app_type|客户端类型|ios、android|
|app_request_time|客户端请求时间戳|单位:秒 时间戳|
|service_type|客户端请求服务|by_Controller_action|
|service_version|客户端请求服务版本|100|
|sign|签名|用户私钥签名 ，公钥验证|
|buss_data|json化 业务参数字符串|rsa sha256方式平台公钥加密|

## 数据加密与解密

### 准备参数
client_id, client_secret   

客户端 保存 平台公钥, 用户私钥

服务端 保留 平台私钥, 用户公钥

buss_data = 对业务参数数组进行按ascii码从小到大排序后进行json化

客户端签名
sign = app_request_time.client_secret.service_type.service_version.buss_data;
sign = base64_encode(Rsa签名 (sign, 用户私钥))
PHP: 
openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
base64_encode($sign);

客户端加密
buss_data = Rsa加密sha256方式 (sign, 平台公钥)
buss_data = encryptChunk(buss_data, 平台公钥)

```
// 公钥加密
public function encryptChunk($originalData, $publicKey)
    {
        $crypto = '';
        $chunk = '';
        foreach (str_split($originalData, 245) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $publicKey);
            $crypto .= $encryptData;
        }
        return base64_encode($crypto);
    }
```

```
// 私钥解密
public function decryptChunk($encryptData, $privateKey)
    {
        $crypto = '';
        $chunk = '';
        foreach (str_split(base64_decode($encryptData), 256) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData, $privateKey);
            $crypto .= $decryptData;
        }
        return $crypto;
    }
```

