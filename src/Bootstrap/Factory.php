<?php

namespace Startcode\CleanCore\Bootstrap;

use Startcode\CleanCore\Request\Request;

class Factory
{
    private $_bootstrap;

    /**
     * @var string
     */
    private $_appNamespace;

    private $_fullBootstrapName;

    /**
     * @var Request
     */
    private $_request;

    public function initBootstrap() : void
    {
        if (!$this->_bootstrap instanceof BootstrapInterface) {
            $this
                ->_constructFullBootstrapName()
                ->_bootstrapFactory();
        }
    }


    public function setAppNamespace(string $appNamespace) : Factory
    {
        $this->_appNamespace = $appNamespace;
        return $this;
    }

    public function setRequest(Request $request) : self
    {
        $this->_request = $request;
        return $this;
    }

    private function _constructFullBootstrapName() : Factory
    {
        $this->_fullBootstrapName = join('\\', array(
            $this->_appNamespace,
            'Bootstrap'
        ));
        return $this;
    }

    private function _bootstrapExist() : bool
    {
        return class_exists($this->_fullBootstrapName);
    }

    private function _bootstrapFactory() : void
    {
        if ($this->_bootstrapExist()) {
            $bootstrapName    = $this->_fullBootstrapName;
            $this->bootstrap = new $bootstrapName();
            $this->bootstrap
                ->setRequest($this->_request)
                ->init();
        }
    }
}
