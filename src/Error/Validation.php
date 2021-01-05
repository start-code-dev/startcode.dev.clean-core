<?php

namespace Startcode\CleanCore\Error;

class Validation
{
    
    /**
     * @var \Startcode\CleanCore\Exception\Validation[]
     */
    private $_exceptions;

    private $_messages;

    public function __construct()
    {
        $this->_exceptions = array();
        $this->_messages   = array();
    }
    
    public function addException(\Startcode\CleanCore\Exception\Validation $exception) : self
    {
        $this->_exceptions[] = $exception;
        return $this;
    }

    public function getMessages() : array
    {
        $this->_iterateTroughExceptions();

        return array(
            'invalid_params' => $this->_messages
        );
    }

    public function hasErrors() : bool
    {
        return !empty($this->_exceptions);
    }
    
    private function _addMessage(\Startcode\CleanCore\Exception\Validation $exception) : void
    {
        $this->_messages[] = [
            $exception->getName() =>  $exception->getValue()
        ];
    }

    private function _iterateTroughExceptions() : void
    {
        foreach($this->_exceptions as $oneException)
        {
            $this->_addMessage($oneException);
        }
    }
}
