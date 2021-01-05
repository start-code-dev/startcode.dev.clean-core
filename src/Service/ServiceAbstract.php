<?php

namespace Startcode\CleanCore\Service;

use Startcode\CleanCore\Response\Response;
use Startcode\CleanCore\Request\Request;
use Startcode\CleanCore\UseCase\UseCaseAbstract;
use Startcode\CleanCore\Validator\Validator;

abstract class ServiceAbstract implements ServiceInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var UseCaseAbstract
     */
    private $useCase;

    /**
     * @var Validator
     */
    private $validator;

    public function areParamsValid() : bool
    {
        return $this->getValidator()
            ->setRequest($this->request)
            ->setMeta($this->getMeta())
            ->setWhitelistParams($this->getWhitelistParams())
            ->isValid();
    }

    public function getFormattedResponse() : Response
    {
        if (!method_exists($this->useCase, 'getFormatterInstance')) {
            $this->response->setResponseObject($this->_getFormattedResource());
        }
        return $this->response;
    }

    public function getValidator() : Validator
    {
        if (!$this->validator instanceof Validator) {
            $this->validator = $this->getValidatorInstance();
        }
        return $this->validator;
    }

    public function getValidatorInstance() : Validator
    {
        return new Validator();
    }

    public function getWhitelistParams() : array
    {
        return array();
    }

    public function run() : self
    {
        $this->areParamsValid()
            ? $this->runUseCase()
            : $this->response
            ->setHttpResponseCode(400)
            ->setResponseMessage($this->getValidator()->getErrorMessages());

        return $this;
    }

    public function getResponse() : Response
    {
        return $this->response;
    }

    public function getRequest() : Request
    {
        return $this->request;
    }

    public function runUseCase() : self
    {
        $this->useCase = $this->getUseCaseInstance();
        $this->useCase
            ->setRequest($this->request)
            ->setResponse($this->response)
            ->run();

        $this->response = $this->useCase->getResponse();

        return $this;
    }

    public function setRequest(Request $request) : self
    {
        $this->request = $request;
        return $this;
    }

    public function setResponse(Response $response) : self
    {
        $this->response = $response;
        return $this;
    }

    private function _getFormattedResource()
    {
        return $this->response->hasResponseObject()
            ? $this->_formatterFactory()
            : null;
    }

    private function _formatterFactory()
    {
        return $this->getFormatterInstance()
            ->setResource($this->response->getResponseObject())
            ->format();
    }
}
