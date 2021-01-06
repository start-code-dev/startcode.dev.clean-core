<?php

namespace Startcode\CleanCore\Buffer\Adapter;

use Predis\Client;

class Redis extends Base
{
    /**
     * @var Client
     */
    protected $_client;

    protected $_parameters;

    public function __construct($parameters = null, $options = null)
    {
        if(!($this->_client instanceof Client)) {
            $this->_client = new Client($parameters, $options);
        }
        $this->_parameters = $parameters;
    }

    public function isClientConnected() : bool
    {
        $uri = "tcp://{$this->_parameters['host']}:{$this->_parameters['port']}/";
        $flags = STREAM_CLIENT_CONNECT;
        $resource = @stream_socket_client($uri, $errno, $errstr, 5, $flags);
        return $resource ? true : false;
    }

    protected function _getClient() : Client
    {
        return $this->_client;
    }

    public function add($key, $value) : bool
    {
        return $this->_getClient()->rPush($key, $value);
    }

    public function overflow(string $key, int $start = null, int $stop = null) : bool
    {
        return $this->_getClient()->lTrim($key, $start, $stop);
    }

    public function used(string $key) : int
    {
        return $this->_getClient()->lLen($key);
    }

    public function read(string $key) : array
    {
        return $this->_getClient()->lRange($key, 0, -1);
    }

    public function del(string $key) : bool
    {
        return $this->_getClient()->del($key);
    }

}
