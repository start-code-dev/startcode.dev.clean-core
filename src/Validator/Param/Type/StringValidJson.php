<?php

namespace Startcode\CleanCore\Validator\Param\Type;
use Startcode\CleanCore\Exception\Validation;

class StringValidJson extends StringValidator
{
    public function cast() : StringValidator
    {
        parent::cast();

        if (!$this->isValueNull()) {
            json_decode($this->_value, true);
            if(json_last_error() !== JSON_ERROR_NONE) {
                throw new Validation($this->_name, $this->_value, $this->_meta);
            }
        }

        return $this;
    }

}
