<?php
/**
 * Abstract model base class providing dependency injection
 * 
 * @author pekkis
 * @package Emerald_Model
 *
 */
abstract class Emerald_Model_AbstractModel
{
    protected $_dependencies = array();

    protected $_rawDependencies;

    protected static $_table = null;


    public function getRawDependencies()
    {
        if(!$this->_rawDependencies) {
            $this->_rawDependencies = $this->_getRawDependencies();
        }
        return $this->_rawDependencies;
    }


    protected function _getRawDependencies()
    {
        $rawDependencies = array();
        if($table = static::$_table) {
            $rawDependencies['table'] = function() use ($table) { return new $table; };
        }
        return $rawDependencies;
    }


    public function getDependency($dependency)
    {
        if(isset($this->_dependencies[$dependency])) {
            return $this->_dependencies[$dependency];
        } else {
            $rawDependencies = $this->getRawDependencies();
            if(!isset($rawDependencies[$dependency])) {
                throw new Emerald_Model_Exception("Dependency {$dependency}' not found for " . get_class($this));
            }
            $this->_dependencies[$dependency] = $rawDependencies[$dependency]();
            return $this->_dependencies[$dependency];
        }
    }


    public function __call($method, $args)
    {
        if(substr($method, 0, 3) == 'get') {
            $dependency = lcfirst(substr($method, 3));
            return $this->getDependency($dependency);
        }

        throw new Emerald_Model_Exception("Magic method " . get_class($this) . "::{$method} not callable.");

    }






}
