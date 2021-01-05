<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class Url extends StringValidator
{
    public function type() : bool
    {
        return parent::type()
            && filter_var($this->_value, FILTER_VALIDATE_URL);
    }
}
