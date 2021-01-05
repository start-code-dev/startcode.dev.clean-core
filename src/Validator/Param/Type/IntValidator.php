<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class IntValidator extends TypeAbstract
{
    public function cast() : self
    {
        if ($this->isValueEmptyString()){
            $this->_value = null;
        }

        if (!$this->isValueNull()) {
            $this->_value = (int) $this->_value;
        }
        return $this;
    }

    public function isValueNull() : bool
    {
        return $this->_value === null;
    }

    public function type() : bool
    {
        return is_int($this->_value);
    }
}
