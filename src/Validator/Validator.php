<?php

namespace Startcode\CleanCore\Validator;

use Startcode\CleanCore\Error\Validation;
use Startcode\CleanCore\Validator\Param\Param;
use Startcode\CleanCore\Request\Request;
use Startcode\CleanCore\Exception\Validation as ValidationException;

class Validator implements ValidatorInterface
{
    const TYPE_ARRAY             = 'ArrayType';
    const TYPE_JSON              = 'Json';
    const TYPE_DATE              = 'Date';
    const TYPE_EMAIL             = 'Email';
    const TYPE_INT               = 'IntValidator';
    const TYPE_INT_POSITIVE      = 'IntPositive';
    const TYPE_INT_NEGATIVE      = 'IntNegative';
    const TYPE_INT_ZERO_POSITIVE = 'IntZeroPositive';
    const TYPE_INT_ZERO_NEGATIVE = 'IntZeroNegative';
    const TYPE_IP                = 'Ip';
    const TYPE_MD5               = 'Md5';
    const TYPE_STRING            = 'StringValidator';
    const TYPE_STRING_VALID_JSON = 'StringValidJson';
    const TYPE_URL               = 'Url';

    /**
     * @var array
     */
    private $meta;

    /**
     * @var array
     */
    private $params;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var bool
     */
    private $valid;

    /**
     * @var Validation
     */
    private $error;

    /**
     * @var array
     */
    private $whitelistParams;


    public function __construct()
    {
        $this->error           = new Validation();
        $this->params          = [];
        $this->valid           = true;
        $this->whitelistParams = [];
    }

    public function getErrorMessages()
    {
        return $this->error->getMessages();
    }

    public function isValid() : bool
    {
        $this->validate();
        $this->request
            ->mergeParams($this->params)
            ->filterParams($this->getWhitelistParams());
        return $this->valid;
    }

    public function setMeta(array $meta) : self
    {
        $this->meta = $meta;
        return $this;
    }

    public function setRequest(Request $request) : self
    {
        $this->request = $request;
        return $this;
    }

    public function setWhitelistParams(array $whitelistParams) : self
    {
        $this->whitelistParams = $whitelistParams;
        return $this;
    }

    private function validate() : void
    {
        $this->iterateTroughMeta();

        if ($this->error->hasErrors()) {
            $this->valid = false;
        }
    }

    private function addToParams($paramName, $meta) : void
    {
        try {
            $param = new Param($paramName, $this->request, $meta);
            $this->params[$paramName] = $param->getValue();
        } catch (ValidationException $exception) {
            $this->error->addException($exception);
        }
    }

    private function getWhitelistParams() : array
    {
        return array_merge($this->whitelistParams, array_keys($this->params));
    }

    private function iterateTroughMeta() : void
    {
        foreach ($this->meta as $paramName => $meta) {
            $this->addToParams($paramName, $meta);
        }
    }
}
