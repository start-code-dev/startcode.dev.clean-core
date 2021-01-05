<?php

namespace Startcode\CleanCore\Validator\Param\Type;
use Startcode\CleanCore\Exception\Validation;

class Json extends ArrayType
{
    public function cast() : self
    {
        if($this->_isNotEmptyString()) {
            $this->_value = json_decode($this->_value, true);
        }

        if($this->_isNotEmptyString() && json_last_error() !== JSON_ERROR_NONE) {
            throw new Validation($this->_name, $this->_value, $this->_meta);
        }

        if($this->isValueEmptyString()) {
            $this->_value = null;
        }

        return $this;
    }
}
