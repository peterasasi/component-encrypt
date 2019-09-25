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


use by\component\encrypt\common\Aes;
use PHPUnit\Framework\TestCase;

class AesTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testTrans()
    {
        $data = base64_encode(str_repeat('helloh', 50));
        $key = 'test';
        $encrypt = Aes::encrypt($data, $key);
        echo $encrypt,"\n";
        $decrypt = Aes::decrypt($encrypt, $key);
        echo base64_decode($decrypt),"\n";
    }


}
