<?php

namespace Startcode\CleanCore;

use Startcode\CleanCore\Controller\Front;
use Startcode\CleanCore\Response\Response;
use Startcode\CleanCore\Request\Request;
use Startcode\CleanCore\Dispatcher\Dispatcher;
use Startcode\CleanCore\Bootstrap\Factory;
use Startcode\CleanCore\Error\Error;

class Application
{
    /**
     * @var Factory
     */
    private $bootstrapFactory;

    /**
     * @var Error
     */
    private $error;

    /**
     * @var Front
     */
    private $frontController;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $appNamespace;

    public function getAppNamespace() : string
    {
        return $this->appNamespace;
    }

    public function getBootstrapFactory() : Factory
    {
        if (!$this->bootstrapFactory instanceof Factory) {
            $this->bootstrapFactory = new Factory();
        }
        return $this->bootstrapFactory;
    }

    public function getError() : Error
    {
        if (!$this->error instanceof Error) {
            $this->error = new Error();
        }
        return $this->error;
    }

    public function getFrontController() : Front
    {
        if (!$this->frontController instanceof Front) {
            $this->frontController = new Front();
        }
        return $this->frontController;
    }

    public function getResponse() : Response
    {
        if (!$this->response instanceof Response) {
            $this->response = new Response();
        }
        return $this->response;
    }

    public function getRequest() : Request
    {
        if (!$this->request instanceof Request) {
            $this->request = new Request();
        }
        return $this->request;
    }

    public function run() : self
    {
        try {
            $this
                ->initBootstrap()
                ->runFrontController();
        } catch(\Exception $exception) {
            $this->getError()
                ->setException($exception)
                ->setResponse($this->getResponse())
                ->manage();
        }
        return $this;
    }

    public function setAppNamespace(string $appNamespace) : self
    {
        $this->appNamespace = $appNamespace;
        return $this;
    }

    public function setRequest(Request $request) : self
    {
        $this->request = $request;
        return $this;
    }

    private function initBootstrap() : self
    {
        $this->getBootstrapFactory()
            ->setAppNamespace($this->appNamespace)
            ->setRequest($this->request)
            ->initBootstrap();
        return $this;
    }

    private function runFrontController() : self
    {
        $this->getFrontController()
            ->setAppNamespace($this->appNamespace)
            ->setDispatcher(new Dispatcher())
            ->setRequest($this->request)
            ->setResponse($this->getResponse())
            ->run();
        $this->response = $this->getFrontController()->getResponse();
        return $this;
    }
}
