<?php

namespace Startcode\CleanCore\Validator;

use Startcode\CleanCore\Request\Request;

interface ValidatorInterface
{
    public function isValid();

    public function setMeta(array $meta);

    public function setRequest(Request $request);
}
