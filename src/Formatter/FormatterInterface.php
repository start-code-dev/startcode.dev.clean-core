<?php

namespace Startcode\CleanCore\Formatter;

interface FormatterInterface
{
    public function format();

    public function setResource($resource);
}
