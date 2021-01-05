<?php

namespace Startcode\CleanCore\Formatter;

use Startcode\CleanCore\Factory\UseCase;
use Startcode\CleanCore\Request\Request;

abstract class FormatterAbstract implements FormatterInterface
{
    /**
     * @var mixed
     */
    private $_resource;

    public function addToResource($part, $value) : self
    {
        $this->_resource[$part] = $value;
        return $this;
    }

    public function doesPartExistsInResource($part) : bool
    {
        return isset($this->_resource[$part]);
    }

    /**
     * @return mixed
     */
    public function getResource(string $part = null)
    {
        return $part === null
            ? $this->_resource
            : $this->_getResourcePart($part);
    }

    /**
     * @return UseCase
     */
    public function getUseCaseFactoryInstance() : UseCase
    {
        return new UseCase();
    }

    public function reference(FormatterAbstract $formatter, $resource)
    {
        return $formatter
            ->setResource($resource)
            ->format();
    }

    public function setResource($resource) : FormatterAbstract
    {
        $this->_resource = $resource;
        return $this;
    }

    /**
     * @return mixed
     */
    public function useCaseReference(string $useCaseName, string $resourcePart = null, Request $request = null)
    {
        $aUseCase = $this->getUseCaseFactoryInstance()
            ->setUseCaseName($useCaseName);

        if ($request instanceof Request) {
            $aUseCase->setRequest($request);
        }

        $aUseCase->getResource($resourcePart);
    }

    /**
     * @param string $part
     * @return mixed
     */
    private function _getResourcePart($part)
    {
        return $this->doesPartExistsInResource($part)
            ? $this->_resource[$part]
            : null;
    }
}
