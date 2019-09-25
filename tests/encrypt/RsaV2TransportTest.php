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


use by\component\encrypt\rsav2\RSAV2Transport;
use PHPUnit\Framework\TestCase;

class RsaV2TransportTest extends TestCase
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

        $transport = new RSAV2Transport('');
        $privateKey = 'LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUV2d0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQktrd2dnU2xBZ0VBQW9JQkFRRDBKaUtVNncrUzg3ZnIKZnhJM0daSUUrVTkrazJWdkphWDhibXBmWUZmcFZsSjl0bFBlNUVrbWlMRkFLTlAxTlFZV01xc1JWUS8yTWJ4ago4QlhzVmZsMzFtWmpHVlA5dkUyNTdxaU9HQ0F1a1YrUWl1UTF6NGxGQ0hTclJoUHdGcHB4Z3BRVVcwb2V5Tk00CndSejdNM05mY1VzalBNdUhWRCtkRTJQb0NKOHE2eVhUeUhVTlNVZFUrSVcxaWxQRTBBZEtLdUc2UUJGU0QzY0kKbnJFWkgxQXRXYXJRNUVqRmtnWDdMRGdGUm5mdlplSFdpcENxeFMvbGloc2JEN29ubUZVV2xXR1dSTmRmTjVXUQpVYUZoa2RoOXEvZEdaa1BSMitqSzU3akNLamFEMSs5enVWSUxkb0VFMDBrUUJQLyt2TGFSS2RIRWNreVpTQ2JaCmFuMVBKSzR4QWdNQkFBRUNnZ0VCQU5jYkprcklVMTlveFhtQjZuMkxWT0IyKzdnTWkwa0RCWnhEZUFyeUttSlcKQUxYcnh6ZFJNTFgzcHh0ZEhXb2tQbW1lV0tRYnFzV0JpbmNPbDNJelNXbHhCNkoyTlU4UDhmZElNMCtHS0F4LwpYcEJPSHNUZVJoWUJYakZzdTdKRForMXNXNlVYelZVVUlTMFd6Nzd1MU02WlZkY0ZBRmtLUEVYYlNLR3R2dmFpCm9JOGU1MzNMclBrWHlNQ0x4VTNWTlhHcFhTMU16T0lGS1UxU0hBUHVtN2FTdmNEQkUrejUrK1F6ODhrNWxkTDIKZGY3VnZSK0RMTzZQZGowNEFGb1JXL0kvaDZSZWRDaXk3WHJydE11cGFWSWF2R3JNY1RuM1NwVEhNWGhQYjY3Zwp3dkRiWDB1a1ZYQjVFRVBDV1BWWWJWOEVRL0tuVTI4OHpNUWJ3TnBmN0dVQ2dZRUEvUHQ2Vkp1K3ZJSXNiajZIClF2TnA4OGdtUVl3azMzclpldXFWNys2VlptS0RoVnRueDZFR0s5QXJlbER6N2VMN1ZPMDVyVVBmaXR1UFk5T3EKWkhhUy82UVcxS1N4clpvOWtEWHZrQzJSWVR3LzAvNEJzVm1ZK3UxaU1PV2k4V2ZiWkk1S2FFWEF4SEw0UGFRawpscnpOZG5qc3BhcGtNR0U2aGRKV05iVXFibXNDZ1lFQTl3K3U0TmN1TnJsQm9yNmtxRStrbWhjU1kyYkg2dkNCCkd5NXRnK3A1bVdXdndTRUYwclVSaDY5REZka0xVVnpUdGdKc3NRZmlSTXpZNWJzbG9EelNkbks5a2ZNYW9ST2oKRlBKWkdFbFFMRXRtOVFMdXNqWmt5bDZqUFRYNDJxcVFlOEw2ZWo3SXVJdCs0bnlnNFFsdFB0UkNvNWlta25wagpPVkpwbVpMV0JOTUNnWUEyWWJqQ1IxaVJIVXAveUdFN095Z2poM3J4ekRhQTg5K2NWS0UybW9yekgybzJXSmZPCmlnYlJsRVpFWFBLU3lLQk9lVkJVdHFwdkp4T0QyaFJlMUQ1MVJjakNuVVMwbE50M0RLRWExVERUUGloYVlkZnIKVDk5YTBYeXlGaXNZeWNLWHN4NjdtNEw4dDlvMUpmdlhpMjUvY1M4dHpac0w2MXF4T2EzZWpiczVVUUtCZ1FDZQptMUhnaENQbCs4NTRSVDE5MUF0TzVRcm9CMzdHZy9uT1VtTDZNaGc0YzBCK2tzMmpOSno1WjNtQTJDTGM2K1A2ClQ5b3BXRzlaVGN6Y3h1Vmoxa2dpeEl5UTJ1bTRpTnZFUWNVU3cxWVY2WjVDSXN6TVdnRWdyZHJNSEE4VGlqWlIKVFozeHhzYWpPdUtOVWdjU0VUUXlCcUIxV0RWdmllU1RVeVg1OG5zMUF3S0JnUURzck1hT0xEY2poQ1BrRWhXMwozSkRuVkZZMWlWRTRnWEY0VitzaGlKMHVyS2pNNWkwc28xYTlGaWNTQzN1c2ZyQnRYZTh2RGRnL0Nhc3RVb2tRCmhrYnhYYXJva0ViY3Jxc05KZ2Q5a0dHM015R254bTNYNlJoa21FZWY0UlpyNVBxa0NmTDdvZU9YS0tMcEx6TncKdFdpY1ZtVUpFbnlBOWpXbU53R0NOaTg0aFE9PQotLS0tLUVORCBQUklWQVRFIEtFWS0tLS0t';
        $data['rsa_key'] = base64_decode($privateKey);
        var_dump($data);
        $encrypt = $transport->encrypt($data);
        var_dump($encrypt);
        echo "**********************\n";
        $publicKey = 'LS0tLS1CRUdJTiBQVUJMSUMgS0VZLS0tLS0KTUlJQklqQU5CZ2txaGtpRzl3MEJBUUVGQUFPQ0FROEFNSUlCQ2dLQ0FRRUE5Q1lpbE9zUGt2TzM2MzhTTnhtUwpCUGxQZnBObGJ5V2wvRzVxWDJCWDZWWlNmYlpUM3VSSkpvaXhRQ2pUOVRVR0ZqS3JFVlVQOWpHOFkvQVY3Rlg1CmQ5Wm1ZeGxUL2J4TnVlNm9qaGdnTHBGZmtJcmtOYytKUlFoMHEwWVQ4QmFhY1lLVUZGdEtIc2pUT01FYyt6TnoKWDNGTEl6ekxoMVEvblJOajZBaWZLdXNsMDhoMURVbEhWUGlGdFlwVHhOQUhTaXJodWtBUlVnOTNDSjZ4R1I5UQpMVm1xME9SSXhaSUYreXc0QlVaMzcyWGgxb3FRcXNVdjVZb2JHdys2SjVoVkZwVmhsa1RYWHplVmtGR2hZWkhZCmZhdjNSbVpEMGR2b3l1ZTR3aW8yZzlmdmM3bFNDM2FCQk5OSkVBVC8vcnkya1NuUnhISk1tVWdtMldwOVR5U3UKTVFJREFRQUIKLS0tLS1FTkQgUFVCTElDIEtFWS0tLS0t';
        $data = $encrypt;
        $data['rsa_key'] = base64_decode($publicKey);
        $data['client_secret'] = 'test';

        $decryptData = $transport->decrypt($data);
        var_dump($decryptData);
    }


}
