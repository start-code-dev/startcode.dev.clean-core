<?php

namespace Startcode\CleanCore\Exception;

class Validation extends \Exception
{
    protected $_meta;

    protected $_name;

    protected $_value;

    public function __construct($name, $value, $meta)
    {
        $this->_name  = $name;
        $this->_value = $value;
        $this->_meta  = $meta;
    }

    public function getName()
    {
        return $this->_name;
    }
    
    public function getValue()
    {
        return $this->_value;
    }
}
