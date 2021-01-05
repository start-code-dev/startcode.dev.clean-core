<?php

namespace Startcode\CleanCore\Validator\Param\Type;

interface TypeInterface
{
    public function cast();

    public function defaultValue();

    public function required();

    public function type();

    public function validValue();
}
