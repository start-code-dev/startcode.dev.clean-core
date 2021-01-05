<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class Md5 extends StringValidator
{
    public function type() : bool
    {
        return parent::type()
            && preg_match('/^[a-f0-9]{32}$/', $this->_value);
    }
}
