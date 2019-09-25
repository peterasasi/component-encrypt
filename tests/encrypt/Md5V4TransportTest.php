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


use by\component\encrypt\md5v4\Md5V4Transport;
use PHPUnit\Framework\TestCase;

class Md5V4TransportTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testTrans()
    {
        $data = [
            'notify_id' => '666',
            'client_id' => 'by04esfH0fdc6Y',
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

        $transport = new Md5V4Transport('');
        $encrypt = $transport->encrypt($data);
        var_dump($encrypt);
        echo "**********************\n";
        $data = $encrypt;
        $data['client_secret'] = 'test';

        $decryptData = $transport->decrypt($data);
        var_dump($decryptData);
    }


}
