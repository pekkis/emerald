<?php
/**
 * Unified object option setter. Somewhat follows Zend Framework 2.0 proposition.
 *
 * @package Emerald_Options
 * @author pekkis
 *
 */
class Emerald_Options
{

    /**
     * Sets object options via compatible setters.
     *
     * @param $object object Object to set
     * @param $options array Options to set
     */
    public static function setOptions($object, array $options)
    {
        if (!is_object($object)) {
            return;
        }
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }
    }

    /**
     * Sets constructor options for an object.
     *
     * @param $object object Object to set
     * @param $options mixed Options to set as zend config or array.
     */
    public static function setConstructorOptions($object, $options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (is_array($options)) {
            self::setOptions($object, $options);
        }
    }
}

