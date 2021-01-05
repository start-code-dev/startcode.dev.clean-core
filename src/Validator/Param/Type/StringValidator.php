<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class StringValidator extends TypeAbstract
{
    public function cast() : self
    {
        if (!$this->isValueNull()) {
            $this->_value = (string) $this->_value;
        }
        return $this;
    }

    public function isValueNull() : bool
    {
        return $this->_value === null || $this->_value === '';
    }

    public function type() : bool
    {
        return is_string($this->_value);
    }
}
