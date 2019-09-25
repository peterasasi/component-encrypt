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


use by\component\encrypt\md5v3\DataStructEntity;
use by\component\encrypt\md5v3\Md5V3Transport;
use PHPUnit\Framework\TestCase;

class Md5V3TransportTest extends TestCase
{

    /**
     * @throws \by\component\encrypt\exception\CryptException
     */
    public function testTrans()
    {
        $data = [
            'accepter' => '123',
            'code_type' => '1',
            'code_create_way' => 6,
            'code_length' => 6];
        $entity = new DataStructEntity();
        $entity->setNotifyId(strval(time()));
        $entity->setClientId('test');
        $entity->setClientSecret('test_secret');
        $entity->setAppType('pc');
        $entity->setAppVersion('100');
        $entity->setType('By_SecurityCode_create');
        $entity->setApiVer('100');
        $entity->setTime(time());
        $entity->setData($data);
        $transport = new Md5V3Transport();
        var_dump($entity->toArray());
        $encrypt = $transport->encrypt($entity->toArray());
        var_dump($encrypt);
        $encrypt['client_secret'] = 'test_secret';
        $transData = [
            "client_id" => "by_common_client_id",
            "asasi" => "M0JLN3FVcWl3dkEya3luU0lxYW1pWDR2bWM3eHpOeGVib2NDNFBqR1hpa3ZtR3ZobTR5TmhPaDBlVklLWEsxWTJIbXcxTm11L2NuT2ZjQy82VGZSbFlYWVFRVm1IUVM1NGdkcnVQdWFzdTMvamg3MkFTek5XMk0wNXZFTW1zWTUzenc5MjZNdEhNcGlwamFTSnZURnM1ODZHQUZ6aFNSMjRWUEppRkdzSWtaOHlqVjBhV0xmWlNmLzYreVFFRW9GOExMU1loUXo5QVhSKzFjWmp3R1hQcHc4dHh3emQrbzBTYldYQjVHUHdVM3Yzd0U0TmVnMzBId1lVS2tFVGtnUFJrZTdRUjh6Qi9PUDZOd0ZXT0tVdkdWZE1sdGdobFk2UUJXMk01Y3EzcHFKeCtWRVQ0WHl4NlhMNGlJTDB2bzRjc0FNVDB6bFJaam01VkxJTXFUSXRSZGdGQjJ1REx0b3dRUlBpdlBWcmg4MmtYRG5aMmt2MDJWMys4TUlvUDBGcGttVEtrTDRkK1A2TG81ZlRUYkpBZ1JvSTQ3Z1hyU05JeHFaN3h2UklubDRLN2hpdXEvbldVYTZ5QjhmNWxrQm9VSGM2TUFQZm9Yb3pNNVYwdVVVcStXSk5iTGk2czNvZkpSTUcwbnFFdEsyV2F4Umw0TCtBVXVXZlFzSWNkbHVBNUIrVjBIN3Y5bjY2WlJTUkJFZU9FMUxGanBzcEV5Nw==",
            "app_type" => "pc",
            "app_version" => "100",
            "service_type" => "By_SecurityCode_create",
            "service_version" => "100",
            "client_secret" => "byTestSecret654321"
        ];
        $decrypt = $transport->decrypt($transData);
        var_dump($decrypt);

    }

}
