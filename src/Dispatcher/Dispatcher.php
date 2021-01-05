<?php

namespace Startcode\CleanCore\Dispatcher;

use Startcode\CleanCore\Service\ServiceAbstract;
use Startcode\CleanCore\Request\Request;

class Dispatcher
{
    /**
     * @var string
     */
    private $_fullServiceName;

    /**
     * @var Request
     */
    private $_request;

    /**
     * @var ServiceAbstract
     */
    private $_service;

    /**
     * @var string
     */
    private $_appNamespace;

    public function __construct()
    {
    }

    public function getService() : ServiceAbstract
    {
        return $this->_service;
    }

    public function isDispatchable() : bool
    {
        $this
            ->constructFullServiceName()
            ->serviceFactory();

        return $this->_service instanceof ServiceAbstract;
    }

    public function setRequest(Request $request) : self
    {
        $this->_request = $request;
        return $this;
    }

    public function setAppNamespace($appNamespace) : Dispatcher
    {
        $this->_appNamespace = $appNamespace;
        return $this;
    }

    private function constructFullServiceName() : Dispatcher
    {
        $this->_fullServiceName = join('\\', array(
            $this->_appNamespace,
            'Service',
            $this->getServiceName(),
            $this->getClassName()
        ));
        return $this;
    }

    private function getClassName() : string
    {
        return ucfirst($this->_request->getMethod());
    }

    private function getServiceName() : string
    {
        return ucfirst(
            preg_replace_callback('/-([a-z])/',
                function($matches) {
                    return strtoupper($matches[1]);
                },
                $this->_request->getResourceName()
            )
        );
    }

    private function serviceExist() : bool
    {
        return class_exists($this->_fullServiceName);
    }

    private function serviceFactory() : void
    {
        if ($this->serviceExist()) {
            $serviceName    = $this->_fullServiceName;
            $this->_service = new $serviceName();
        }
    }
}
