<?php

namespace Startcode\CleanCore\Request;

class Request
{

    /**
     * @var bool
     */
    private $ajaxCall;

    /**
     * @var array
     */
    private $anonymizationRules;

    /**
     * Valid methods
     * index, get, post, put, delete
     * @var string
     */
    private $_method;

    /**
     * @var string
     */
    private $_module;

    /**
     * @var array
     */
    private $_params;

    /**
     * @var string
     */
    private $rawInput;

    /**
     * Resource name
     * @var string
     */
    private $_resourceName;

    /**
     * @var array
     */
    private $serverVariables;

    public function __construct()
    {
        $this->_params = array();
    }

    public function filterParams(array $whitelistParamKeys) : self
    {
        $this->_params = array_intersect_key($this->_params, array_flip($whitelistParamKeys));
        return $this;
    }

    public function getMethod() : string
    {
        return $this->_method;
    }

    public function getModule() : string
    {
        return $this->_module;
    }

    /**
     * @return null|int|string|array
     */
    public function getParam(string $key)
    {
        return $this->hasParam($key)
            ? $this->_params[$key]
            : null;
    }

    public function getParams() : ?array
    {
        return $this->_params;
    }

    public function getRawInput() : string
    {
        return $this->rawInput;
    }

    public function getServerVariable(string $key) : ?string
    {
        return is_array($this->serverVariables) && isset($this->serverVariables[$key])
            ? $this->serverVariables[$key]
            : null;
    }

    public function setAnonymizationRules($param, $rule) : self
    {
        if(isset($this->anonymizationRules[$param])) {
            throw new \Exception("Rule for '{$param}' already exists.");
        }

        $this->anonymizationRules[$param] = $rule;
        return $this;
    }

    public function getParamsAnonymized()
    {
        $cleanParams = $this->getParams();

        if(is_array($this->anonymizationRules)) {
            foreach ($this->anonymizationRules as $key => $rule) {
                if(isset($cleanParams[$key])) {
                    if($rule === null) {
                        unset($cleanParams[$key]);
                    } else {
                        $cleanParams[$key] = is_callable($rule)
                            ? $rule($cleanParams[$key])
                            : $rule;
                    }
                }
            }
        }

        return $cleanParams;
    }

    public function getResourceName() : ?string
    {
        return $this->_resourceName;
    }

    public function hasParam(string $key) : bool
    {
        return isset($this->_params[$key]);
    }

    public function isAjax() : bool
    {
        return (bool) $this->ajaxCall;
    }

    public function mergeParams($params) : self
    {
        $this->_params = array_merge($this->_params, $params);
        return $this;
    }

    public function setAjaxCall($ajaxCall) : self
    {
        $this->ajaxCall = $ajaxCall;
        return $this;
    }

    public function setMethod(string $method) : self
    {
        $this->_method = $method;
        return $this;
    }

    public function setModule(string $module) : self
    {
        $this->_module = $module;
        return $this;
    }

    public function setParam(string $key, $value) : self
    {
        $this->_params[$key] = $value;
        return $this;
    }

    /**
     * @param array|string $params
     */
    public function setParams($params) : self
    {
        is_array($params)
            ? ($this->_params = $params)
            : parse_str($params, $this->_params);

        return $this;
    }

    public function setRawInput(string $value) : self
    {
        $this->rawInput = $value;
        return $this;
    }

    public function setResourceName(string $resourceName) : self
    {
        $this->_resourceName = $resourceName;
        return $this;
    }

    public function setServerVariables(array $value) : self
    {
        $this->serverVariables = $value;
        return $this;
    }

    public function unsetParam(string $key) : self
    {
        unset($this->_params[$key]);
        return $this;
    }
}
