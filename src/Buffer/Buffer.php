<?php

namespace Startcode\CleanCore\Buffer;

use Startcode\CleanCore\Buffer\Adapter\Base;

class Buffer
{

    const ADAPTER_REDIS = 'Redis';

    protected $_adapter = null;

    protected $_availableAdapters = array(
        self::ADAPTER_REDIS,
    );
    protected $_defaultAdapter = self::ADAPTER_REDIS;

    protected $_cacheKey;

    /**
     * Overflow callback function
     *
     * @var \Closure
     */
    protected $_overflowCallback;

    /**
     * Class constructor
     *
     * @param  string  $namespace
     * @param  mixed   $adapter
     * @param  mixed   $size
     * @param  \Closure $callback
     * @param  array   $options
     * @return void
     */
    public function __construct($namespace, $adapter = null, $size = null, \Closure $callback = null, $options = null)
    {
        $adapter = in_array($adapter, $this->_availableAdapters)
            ? $adapter
            : $this->_defaultAdapter;

        $class = 'Startcode\\Buffer\\Adapter\\' . $adapter;

        // client options
        if(!isset($options['parameters'])) {
            $options['parameters'] = null;
        }

        if(!isset($options['options'])) {
            $options['options'] = null;
        }

        $this->_adapter = new $class($options['parameters'], $options['options']);

        $this->_setCacheKey($namespace);
        $this->setSize($size);

        if(null !== $callback && is_callable($callback)) {
            $this->_overflowCallback = $callback;
        }
    }

    protected function _getAdapter() : Base
    {
        return $this->_adapter;
    }

    protected function _setCacheKey(string $namespace)  : void
    {
        $this->_cacheKey = $namespace . '_' . substr(md5($namespace), 0, 10);
    }

    public function setSize(int $size = null)  : void
    {
        $this->_getAdapter()->setSize($size);
    }

    protected function _overflowHandler() : bool
    {
        return $this->_getAdapter()->overflow(
            $this->_cacheKey,
            $this->getUsage() - $this->getSize() + 1,
            $this->getUsage()
        );
    }

    public function add($data) : bool
    {
        if(empty($data) || !$this->_getAdapter()->isClientConnected()) {
            return false;
        }

        if(!$this->_getAdapter()->add($this->_cacheKey, json_encode($data))) {
            return false;
        }

        // overflow handling
        if($this->getUsage() >= $this->getSize()) {
            if (!empty ($this->_overflowCallback) && is_callable ($this->_overflowCallback)) {
                call_user_func_array ($this->_overflowCallback, array ($this->readAndFlush()));
            } else {
                $this->_overflowHandler();
            }
        }
        return  false;
    }

    /**
     * @return mixed
     */
    public function read()
    {
        $data = $this->_getAdapter()->read($this->_cacheKey);

        if(!empty($data) && is_array($data)) {
            foreach($data as &$row) {
                $row = json_decode($row, 1);
            }
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function readAndFlush()
    {
        $data = $this->read();
        $this->flush();
        return $data;
    }

    public function getSize() : int
    {
        return $this->_getAdapter()->getSize();
    }

    public function getUsage() : int
    {
        return $this->_getAdapter()->used($this->_cacheKey);
    }

    public function flush()
    {
        return $this->_getAdapter()->del($this->_cacheKey);
    }

}
