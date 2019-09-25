<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    asasi<>
 * @copyright 2017 www.asasi.com Boye Inc. All rights reserved.
 * @link      http://www.asasi.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-05 16:29
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace by\component\encrypt\interfaces;


interface TransportInterface
{


    /**
     * 加密传输数据
     * @param $data
     * @return mixed
     */
    function encrypt($data);


    /**
     * 加密数据
     * @param $data
     * @return mixed
     */
    function encryptInnerData($data);

    /**
     * 解密传输数据
     * @param $data
     * @return mixed
     */
    function decrypt($data);

    /**
     * 解密数据
     * @param $data
     * @return mixed
     */
    function decryptInnerData($data);
}
