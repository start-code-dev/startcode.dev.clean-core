<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class Email extends StringValidator
{
    public function type() : bool
    {
        $this->_value = preg_replace('/\s+/', '+', trim($this->_value));
        return parent::type()
            && filter_var($this->_value, FILTER_VALIDATE_EMAIL);
    }
}
