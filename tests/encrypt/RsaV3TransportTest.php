<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    asasi<>
 * @copyright 2017 www.asasi.com Boye Inc. All rights reserved.
 * @link      http://www.asasi.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-15 19:10
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace byTest\encrypt;


use by\component\encrypt\rsa\Rsa;
use by\component\encrypt\rsav3\RSAV3Transport;
use PHPUnit\Framework\TestCase;

class RsaV3TransportTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testTrans()
    {
        $data = [
            'notify_id' => '666',
            'client_id' => 'client_id',
            'client_secret' => 'test',
            'app_request_time' => '1530528254',
            'service_type' => 'by_Clients_check',
            'service_version' => '100',
            'app_type' => 'ios',
            'app_version' => '1.0.0',
            'lang' => 'zh-cn',
            'buss_data' => [
                'test' => 'success'
            ]
        ];

        $transport = new RSAV3Transport('');
        list($myPublicKey, $myPrivateKey) = Rsa::generateRsaKeys();
        list($sysPublicKey, $sysPrivateKey) = Rsa::generateRsaKeys();

        $data['sys_public_key'] = $sysPublicKey;
        $data['my_private_key'] = $myPrivateKey;
        var_dump($data);
        $encrypt = $transport->clientEncrypt($data);
        var_dump($encrypt);
        echo "**********************\n";
        $encrypt['my_public_key'] = $myPublicKey;
        $encrypt['sys_private_key'] = $sysPrivateKey;
        $encrypt['client_secret'] = 'test';
        $decrypt = $transport->decrypt($encrypt);
        var_dump($decrypt);
    }

}
