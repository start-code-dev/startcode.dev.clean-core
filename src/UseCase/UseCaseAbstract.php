<?php

namespace Startcode\CleanCore\UseCase;

use Startcode\CleanCore\Factory\UseCase;
use Startcode\CleanCore\Response\Response;
use Startcode\CleanCore\Request\Request;

abstract class UseCaseAbstract implements UseCaseInterface
{
    /**
     * @var Request
     */
    private $_request;

    /**
     *
     * @var Response
     */
    private $_response;

    private $_formatter;

    public function __construct()
    {
        $this->setResponse(new Response());
    }

    public function forward(string $useCaseName)
    {
        $this->getResponse()->setResponseObject(
            $this->getUseCaseFactoryInstance()
            ->setUseCaseName($useCaseName)
            ->setRequest($this->getRequest())
            ->getResource());
    }

    public function getRequest() : Request
    {
        return $this->_request;
    }

    public function getResponse() : Response
    {
        if (method_exists($this, 'getFormatterInstance') && $this->_response->hasResponseObject()) {
            $formattedResource = $this->getFormatterInstance()
                ->setResource($this->_response->getResponseObject())
                ->format();

            $this->_response->setResponseObject($formattedResource);
        }

        return $this->_response;
    }

    public function getUseCaseFactoryInstance() : UseCase
    {
        return new UseCase();
    }

    /**
     * Factory method for use of a new UseCase class
     * Returns whole resource or just one part
     * @return mixed
     */
    public function reference(string $useCaseName, string $resourcePart = null, Request $request = null)
    {
        if(null === $request) {
            $request = $this->getRequest();
        }
        return $this->getUseCaseFactoryInstance()
            ->setUseCaseName($useCaseName)
            ->setRequest($request)
            ->getResource($resourcePart);
    }

    public function setRequest(Request $request) : UseCaseAbstract
    {
        $this->_request = $request;
        return $this;
    }

    public function setResponse(Response $response) : UseCaseAbstract
    {
        $this->_response = $response;
        return $this;
    }
}
