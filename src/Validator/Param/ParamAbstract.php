<?php

namespace Startcode\CleanCore\Validator\Param;

use Startcode\CleanCore\Request\Request;

abstract class ParamAbstract implements ParamInterface
{
    /**
     * Possible options:
     *     default
     *     required
     *     separator
     *     type
     *     valid
     *
     * @var array
     */
    protected $_meta;

    protected $_name;

    protected $_value;

    private $request;

    public function __construct($name, Request $request, $meta)
    {
        $this->_name   = $name;
        $this->request = $request;
        $this->_value  = $request->getParam($name);
        $this->_meta   = $meta;
    }

    public function getRequest() : Request
    {
        return $this->request;
    }
}
