<?php


namespace by\component\encrypt\interfaces;


interface ClientTransportInterface
{

    function clientDecrypt($data);

    function clientEncrypt($data);
}
