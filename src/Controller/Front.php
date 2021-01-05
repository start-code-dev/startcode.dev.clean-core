<?php

namespace Startcode\CleanCore\Controller;

use Startcode\CleanCore\Request\Request;
use Startcode\CleanCore\Response\Response;
use Startcode\CleanCore\Dispatcher\Dispatcher;
use Startcode\CleanCore\Service\ServiceAbstract;

class Front
{
    private $_appNamespace;

    /**
     * @var Dispatcher
     */
    private $_dispatcher;

    /**
     * @var ServiceAbstract
     */
    private $_service;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var Response
     */
    private $_response;

    public function getResponse() : Response
    {
        return $this->_response;
    }

    public function run() : void
    {
        $this->_dispatcher
            ->setRequest($this->_request)
            ->setAppNamespace($this->_appNamespace);

        $this->_dispatcher->isDispatchable()
            ? $this->_dispatch()
            : $this->_response->setHttpResponseCode(404);

    }

    public function setAppNamespace($appNamespace) : self
    {
        $this->_appNamespace = $appNamespace;
        return $this;
    }

    public function setDispatcher(Dispatcher $dispatcher) : self
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }

    public function setRequest(Request $request) : self
    {
        $this->_request = $request;
        return $this;
    }

    public function setResponse(Response $response) : self
    {
        $this->_response = $response;
        return $this;
    }

    private function _dispatch() : void
    {
        $this->_service = $this->_dispatcher->getService();
        $this->_service
            ->setRequest($this->_request)
            ->setResponse($this->_response)
            ->run();

        $this->_response = $this->_service->getFormattedResponse();
    }
}
