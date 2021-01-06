<?php

namespace Startcode\CleanCore\Buffer\Adapter;

abstract class Base
{

    const DEFAULT_SIZE = 1000;

    protected $_size = self::DEFAULT_SIZE;

    public function __construct($parameters = null, $options = null)
    {
        throw new \Exception('Must implement this in child class');
    }

    abstract public function add($key, $value);

    abstract public function overflow(string $key, int $start = null, int $stop = null);

    abstract public function used(string $key);

    abstract public function read(string $key);

    abstract public function del(string $key);

    public function setSize(int $size = null) : void
    {
        $this->_size = $size ?? self::DEFAULT_SIZE;
    }

    public function getSize() : int
    {
        return $this->_size;
    }

}
