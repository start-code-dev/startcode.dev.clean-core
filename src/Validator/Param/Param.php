<?php

namespace Startcode\CleanCore\Validator\Param;

class Param extends ParamAbstract
{
    public function getValue()
    {
        return $this->factory()->getValue();
    }

    private function factory()
    {
        $className = class_exists($this->_meta['type'])
            ? $this->_meta['type']
            : 'Startcode\CleanCore\Validator\Param\Type\\' . $this->_meta['type'];
        return new $className($this->_name, $this->getRequest(), $this->_meta);
    }
}
