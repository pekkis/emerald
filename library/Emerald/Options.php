<?php
class Emerald_Options 
{ 
    public static function setOptions($object, array $options) 
    { 
        if (!is_object($object)) { 
            return; 
        } 
        foreach ($options as $key => $value) { 
            $method = 'set' . $key; 
            if (method_exists($object, $method)) { 
                $object->$method($value); 
            } 
        } 
    } 
 
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
 
