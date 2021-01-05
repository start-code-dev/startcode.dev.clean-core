<?php

namespace Startcode\CleanCore\Factory;

use Startcode\CleanCore\Request\Request;
use Startcode\CleanCore\Response\Response;
use Startcode\CleanCore\UseCase\UseCaseAbstract;

class UseCase
{
    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Response
     */
    private $_response;

    /**
     * @var UseCaseAbstract
     */
    private $_useCase;

    /**
     * @var string
     */
    private $_useCaseName;

    /**
     * @throws \Exception
     */
    public function getRequest() : Request
    {
        if (!$this->_request instanceof Request) {
            throw new \Exception('Request must be instance of \Startcode\CleanCore\Request\Request');
        }
        return $this->_request;
    }

    /**
     * @return mixed
     */
    public function getResource(string $part = null)
    {
        return $part === null
            ? $this->getResponse()->getResponseObject()
            : $this->getResponse()->getResponseObjectPart($part);
    }

    public function getResponse() : Response
    {
        if (!$this->_response instanceof Response) {
            $this->_setResponse();
        }
        return $this->_response;
    }

    /**
     * @throws \Exception
     */
    public function getUseCase() : UseCaseAbstract
    {
        if (!$this->_useCase instanceof UseCaseAbstract) {
            $this->_factory();
        }
        return $this->_useCase;
    }

    public function setRequest(Request $request) : self
    {
        $this->_request = $request;
        return $this;
    }

    public function setUseCaseName($useCaseName) : self
    {
        $this->_useCaseName = $useCaseName;
        return $this;
    }

    private function _factory() : self
    {
        $useCaseName    = $this->_useCaseName;
        $this->_useCase = new $useCaseName();
        $this->_useCase
            ->setRequest($this->getRequest());
        return $this;
    }

    private function _setResponse() : self
    {
        $this->getUseCase()->run();
        $this->_response = $this->getUseCase()->getResponse();
        return $this;
    }
}
