<?php

namespace Startcode\CleanCore\Meta;

class Meta
{

    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function add($valueObject) : void
    {
        $this->data[] = $valueObject;
    }


}
