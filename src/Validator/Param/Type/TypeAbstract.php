<?php

namespace Startcode\CleanCore\Validator\Param\Type;

use Startcode\CleanCore\Validator\Param\ParamAbstract;
use Startcode\CleanCore\Exception\Validation;

abstract class TypeAbstract extends ParamAbstract implements TypeInterface
{
    const ARRAY_VALUE_SEPARATOR = '|';

    public function defaultValue() : TypeAbstract
    {
        if ($this->hasDefault() && $this->isValueNull()) {
            $this->_value = $this->getDefaultValues();
        }
        return $this;
    }

    public function getArrayValueSeparator() : string
    {
        return !empty($this->_meta['separator']) && is_string($this->_meta['separator'])
            ? $this->_meta['separator']
            : self::ARRAY_VALUE_SEPARATOR;
    }

    public function getDefaultValues()
    {
        return is_callable($this->_meta['default'])
            ? call_user_func($this->_meta['default'])
            : $this->_meta['default'];
    }

    public function getValidValues()
    {
        return is_callable($this->_meta['valid'])
            ? call_user_func($this->_meta['valid'])
            : (is_array($this->_meta['valid']) ? $this->_meta['valid'] : array());
    }

    public function getValue()
    {
        $this->cast()
            ->validValue()
            ->validType()
            ->required()
            ->defaultValue();

        return $this->_value;
    }

    public function isRequiredMetaSet() : bool
    {
        return isset($this->_meta['required'])
            && $this->_meta['required'] === true;
    }

    public function isRequiredNotSet() : bool
    {
        return $this->isRequiredMetaSet()
            && ($this->isValueNull() || !$this->type());
    }

    public function isInValidRange() : bool
    {
        return in_array($this->_value, $this->getValidValues());
    }

    public function isValidMetaSet() : bool
    {
        return isset($this->_meta['valid']);
    }

    public function isValueEmptyString() : bool
    {
        return $this->_value === '';
    }

    public function isValueNull() : bool
    {
        return $this->_value === null
            || (empty($this->_value)
                && (is_array($this->_value) || is_string($this->_value)));
    }

    public function hasDefault() : bool
    {
        return isset($this->_meta['default']);
    }

    public function required() : self
    {
        if ($this->isRequiredNotSet()) {
            throw new Validation($this->_name, $this->_value, $this->_meta);
        }
        return $this;
    }

    public function validType() : self
    {
        if (!$this->isValueNull() && !$this->type()) {
            throw new Validation($this->_name, $this->_value, $this->_meta);
        }
        return $this;
    }

    public function validValue() : self
    {
        if ($this->isValidMetaSet() && !$this->isInValidRange()) {
            $this->_value = null;
        }
        return $this;
    }
}
