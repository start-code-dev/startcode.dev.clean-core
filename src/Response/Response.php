<?php

namespace Startcode\CleanCore\Response;

class Response
{
    private $_applicationResponseCode;

    private $_formattedResource;

    private $_httpResponseCode;

    private $_responseMessage;

    private $_rawResource;

    public function __construct()
    {
    }

    public function addPartToResponseObject($key, $value) : self
    {
        $this->_rawResource[$key] = $value;
        return $this;
    }

    public function getApplicationResponseCode() : ?int
    {
        return $this->_applicationResponseCode;
    }

    public function getFormattedResource()
    {
        return $this->_formattedResource;
    }

    public function getHttpResponseCode() : ?int
    {
        if ($this->_httpResponseCode === null) {
            $this->_httpResponseCode = $this->hasResponseObject() ? 200 : 204;
        }
        return $this->_httpResponseCode;
    }

    public function getHttpMessage() : string
    {
        return Code::asMessage($this->getHttpResponseCode());
    }

    public function getResponseMessage()
    {
        return $this->_responseMessage;
    }

    public function getResponseObject()
    {
        return $this->_rawResource;
    }

    public function getResponseObjectPart($key)
    {
        return $this->_rawResource[$key] ?? null;
    }

    public function hasResponseObject() : bool
    {
        return isset($this->_rawResource);
    }

    public function setApplicationResponseCode($applicationResponseCode) : self
    {
        $this->_applicationResponseCode = $applicationResponseCode;
        return $this;
    }

    public function setFormattedResource($formattedResource) : self
    {
        $this->_formattedResource = $formattedResource;
        return $this;
    }

    public function setHttpResponseCode($httpResponseCode) : self
    {
        $this->_httpResponseCode = $httpResponseCode;
        return $this;
    }

    public function setResponseMessage($value) : self
    {
        $this->_responseMessage = $value;
        return $this;
    }

    public function setResponseObject($rawResource) : self
    {
        $this->_rawResource = $rawResource;
        return $this;
    }
}
