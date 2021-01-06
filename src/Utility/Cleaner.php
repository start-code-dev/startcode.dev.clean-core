<?php

namespace Startcode\CleanCore\Utility;

class Cleaner
{
    public function __construct()
    {
        if(!defined('DEBUG') || DEBUG !== true) {
            throw new \Exception("Still experimental, not safe to be used in production");
        }
    }

    /**
     * Takes variable and returns the same if is set otherwise returns $default
     *
     * @param mixed $variable - variable
     * @param mixed $default - default value if variable not set
     * @param mixed $allowed - array of allowed values
     * @param bool $escape - escape quotes
     * @return mixed
     */
    public function get($variable, $default = '', $allowed = '', $escape = true)
    {
        // if this is an array or object, don't destroy it, just return
        if(isset($variable) && (is_array($variable) || is_object($variable))) {
            return $variable;
        }

        // now check if is set?
        $variable = isset($variable) ? trim($variable) : $default;

        // is in allowed range?
        if(!empty($allowed) && is_array($allowed)) {
            if(!in_array($variable, $allowed)) {
                $variable = reset($allowed);
            }
        }

        return $escape ? addslashes($variable) : $variable;
    }

    /**
     * Same as _get, but it rawurldecodes the string
     *
     * @param mixed $variable - variable
     * @param mixed $default - default value if variable not set
     * @param mixed $allowed - array of allowed values
     * @param bool $escape - escape quotes
     */
    public function getHtml($variable, $default = '', $allowed = '', bool $escape = true) : string
    {
        return (string) rawurldecode($this->get($variable, $default, $allowed, $escape));
    }

    /**
     * Same as _getHtml, but it also strips tags
     *
     * @param mixed $variable - variable
     * @param mixed $default - default value if variable not set
     * @param mixed $allowed - array of allowed values
     * @param bool $escape - escape quotes
     */
    public function getString($variable, $default = '', $allowed = '', bool $escape = true) : string
    {
        return (string) strip_tags($this->getHtml($variable, $default, $allowed, $escape));
    }

    /**
     * Same as _get but casts return value to integer
     *
     * @access public
     * @param mixed $variable - variable
     * @param mixed $default - default value if variable not set
     * @param mixed $allowed - array of allowed values
     */
    public function getInt($variable, $default = 0, $allowed = '') : int
    {
        return (int) $this->get($variable, $default, $allowed, false);
    }

    /**
     * Same as _get but casts return value to float
     *
     * @access public
     * @param mixed $variable - variable
     * @param float $default - default value if variable not set
     * @param mixed $allowed - array of allowed values
     */
    public function getFloat($variable, $default = 0.0, $allowed = '') : float
    {
        return (float) $this->get($variable, $default, $allowed, false);
    }

    /**
     * Same as _get but casts return value to bollean
     *
     * @access public
     * @param mixed $variable - variable
     * @param bool $default
     */
    public function getBool($variable, $default = false) : bool
    {
        return (bool) $this->get($variable, $default, array(false, true), false);
    }

    /**
     * Replacement for htmlspecialchars
     *
     * @param string $string
     * @param int $quoteStyle default ENT_QUOTES
     * @param string $charset default UTF-8
     * @param bool $doubleEncode - when double_encode is turned off PHP will not encode existing html entities
     */
    public function htmlEncode($string, $quoteStyle = ENT_QUOTES, $charset = 'UTF-8', $doubleEncode = false) : string
    {
        return htmlspecialchars((string) $string, $quoteStyle, $charset, $doubleEncode);
    }

    /**
     * Reverse of htmlEncode
     *
     * @param mixed $string
     * @param mixed $quoteStyle default ENT_QUOTES
     */
    public function htmlDecode($string, $quoteStyle = ENT_QUOTES) : string
    {
        return htmlspecialchars_decode((string) $string, $quoteStyle);
    }
}
