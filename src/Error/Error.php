<?php

namespace Startcode\CleanCore\Error;

use Startcode\CleanCore\Constants\Http;
use Startcode\CleanCore\Response\Code;
use Startcode\CleanCore\Response\Response;

class Error
{
    private $_exception;

    private $_response;

    public function manage() : void
    {
        $this->_response
            ->setResponseMessage($this->_getFormattedResponseMessage())
            ->setApplicationResponseCode($this->_exception->getCode())
            ->setHttpResponseCode($this->_getHttpCode())
            ->setResponseObject([
                'error' => [
                    'code'    => $this->_exception->getCode(),
                    'message' => $this->_exception->getMessage(),
                ]
            ]);
    }

    public function setException(\Exception $exception) : self
    {
        $this->_exception = $exception;
        return $this;
    }

    public function setResponse(Response $response) : self
    {
        $this->_response = $response;
        return $this;
    }

    private function _getHttpCode() : int
    {
        return Code::isValid($this->_exception->getCode())
            ? $this->_exception->getCode()
            : Http::CODE_500;
    }

    private function _getFormattedResponseMessage() : array
    {
        return array(
            'code'    => $this->_exception->getCode(),
            'message' => $this->_exception->getMessage(),
            'file'    => $this->_exception->getFile(),
            'line'    => $this->_exception->getLine(),
            'trace'   => $this->_exception->getTrace()
        );
    }
}
