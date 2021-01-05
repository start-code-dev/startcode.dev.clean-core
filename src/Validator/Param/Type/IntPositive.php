<?php

namespace Startcode\CleanCore\Validator\Param\Type;

class IntPositive extends IntValidator
{
    public function type() : bool
    {
        return parent::type() && $this->_value > 0;
    }
}
