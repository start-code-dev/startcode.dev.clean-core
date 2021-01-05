<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class Ip extends StringValidator
{
    public function type() : bool
    {
        return parent::type()
            && filter_var($this->_value, FILTER_VALIDATE_IP);
    }
}
