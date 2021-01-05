<?php

namespace Startcode\CleanCore\Formatter;

final class Gateway extends FormatterAbstract
{

    public function format()
    {
        $data = $this->getResource('data');
        return empty($data)
            ? []
            : $data;
    }
}
