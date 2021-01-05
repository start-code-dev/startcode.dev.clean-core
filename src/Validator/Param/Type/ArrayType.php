<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class ArrayType extends TypeAbstract
{
    public function cast() : self
    {
        if ($this->_isNotEmptyString()) {

            $this->_value = explode($this->getArrayValueSeparator(), $this->_value);
        }

        if ($this->isValueEmptyString()) {

            $this->_value = null;
        }

        return $this;
    }

    public function type() : bool
    {
        return is_array($this->_value);
    }

    public function validValue() : self
    {
        if ($this->isValidMetaSet() && is_array($this->_value)) {
            $this->_value = array_intersect($this->getValidValues(), $this->_value);
        }
        return $this;
    }

    protected function _isNotEmptyString() : bool
    {
        return !$this->isValueNull()
            && is_string($this->_value)
            && !$this->isValueEmptyString();
    }
}
