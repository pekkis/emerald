<?php

namespace Emerald\Filelib;

/**
 * Abstract filelib item class
 *
 * @author pekkis
 *
 *
 */
abstract class AbstractItem
{

    /**
     * Enforce field integrity
     *
     * @var unknown_type
     */
    protected $_enforceFieldIntegrity = false;


    /**
     * @var array Item data
     */
    protected $_data = array();

    /**
     * Constructor fills item with data if specified. If an object is given, toArray is called.
     *
     * @param object|array $data
     */
    public function __construct($data = array())
    {
        if(is_object($data)) {
            $data = $data->toArray();
        }

        if(!is_array($data)) {
            throw new \Emerald\Filelib\FilelibException('Supplied data must be an array');
        }

        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }


    /**
     * Sets whether field integrity is enforced (exception thrown when accessing unaccessible)
     *
     * @param boolean $enforceFieldIntegrity
     */
    public function enforceFieldIntegrity($enforceFieldIntegrity)
    {
        $this->_enforceFieldIntegrity = (bool) $enforceFieldIntegrity;
    }



    /**
     * Returns an array representation of the item.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_data;
    }


    /**
     * Sets from array
     *
     * @param array $array Data
     */
    public function setFromArray(array $array)
    {
        $this->_data = array_merge($this->_data, $array);

    }



    /**
     * Automatic setter sets a field automagically
     *
     * @param string $key Key
     * @param mixed $value Value
     */
    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }


    public function __isset($key)
    {
        return array_key_exists($key, $this->_data);
    }



    /**
     * Automatic getter returns a field if it exists.
     *
     * @param string $key Key
     * @return mixed
     * @throws \Emerald\Filelib\FilelibException
     */
    public function __get($key)
    {
        if(!array_key_exists($key, $this->_data)) {
            	
            if(!$this->_enforceFieldIntegrity) {
                return null;
            }
            throw new \Emerald\Filelib\FilelibException("Field '{$key}' not set");
            	
        }
        return $this->_data[$key];
    }


    
    public function __unset($key)
    {
    	if(isset($this->_data[$key])) {
    		unset($this->_data[$key]);    		
    	}
    }


}