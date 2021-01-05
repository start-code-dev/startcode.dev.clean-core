<?php

namespace Startcode\CleanCore\Bootstrap;

use Startcode\CleanCore\Request\Request;

class BootstrapAbstract
{
    /**
     * @var Request
     */
    protected $_request;

    public function setRequest(Request $request) : self
    {
        $this->_request = $request;
        return $this;
    }

    public function getRequest() : Request
    {
        return $this->_request;
    }
}
