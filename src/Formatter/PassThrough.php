<?php

namespace Startcode\CleanCore\Formatter;

final class PassThrough extends FormatterAbstract
{

    public function format()
    {
        return $this->getResource();
    }
}
