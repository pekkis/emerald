<?php
/**
 * Autoloading, autocaching extension of Zend_Acl
 *
 * @author pekkis
 * @package Emerald_Acl
 *
 */
class Emerald_Acl extends Zend_Acl
{

    /**
     * Cache
     * @var Zend_Cache_Core
     */
    private $_cache;
    
    /**
     * Resource autoloaders
     * @var array
     */
    private $_resourceAutoloaders = array();

    /**
     * Role autoloaders
     * @var array
     */
    private $_roleAutoloaders = array();
    
    /**
     * Adds a resource autoloader
     * 
     * @param string $regex Recognition pattern
     * @param callback $callback Autoloader callback
     * @throws Zend_Acl_Exception
     */
    public function addResourceAutoloader($regex, $callback)
    {
        if(!is_callable($callback)) {
            throw new Zend_Acl_Exception("Acl autoloader callback is not callable");
        }
        
        if(!is_array($regex)) {
            $regex = array($regex);
        }
        
        foreach($regex as $regx) {
            $this->_resourceAutoloaders[$regx] = $callback;    
        }
    }

    
    /**
     * Removes a resource autoloader
     * @param string $regex Pattern to remove
     */
    public function removeResourceAutoloader($regex)
    {
        if(isset($this->_resourceAutoloaders[$regex])) {
            unset($this->_resourceAutoloaders[$regex]);
        }
    }
    
    /**
     * Adds a role autoloader
     * 
     * @param string $regex Recognition pattern
     * @param callback $callback Autoloader callback
     * @throws Zend_Acl_Exception
     */
    public function addRoleAutoloader($regex, $callback)
    {
        if(!is_callable($callback)) {
            throw new Zend_Acl_Exception("Acl role autoloader callback is not callable");
        }
        
        if(!is_array($regex)) {
            $regex = array($regex);
        }
        
        foreach($regex as $regx) {
            $this->_roleAutoloaders[$regx] = $callback;    
        }
        
    }
    
    /**
     * Removes a role autoloader
     * @param string $regex Pattern to remove
     */
    public function removeRoleAutoloader($regex)
    {
        if(isset($this->_roleAutoloaders[$regex])) {
            unset($this->_roleAutoloaders[$regex]);
        }
    }
    
    
    /**
     * Autoloads a resource
     * 
     * @param Emerald_Acl_ResourceInterface|string $resource
     * @throws Zend_Acl_Exception
     * @return Emerald_Acl_ResourceInterface
     */
    public function autoloadResource($resource)
    {
        if(!$resource instanceof Emerald_Acl_ResourceInterface) {
            
            // Not of the interface, iterate all autoloaders and autoload when match
            $origResource = $resource;
            foreach($this->_resourceAutoloaders as $regex => $callback) {
                if(preg_match($regex, $resource)) {
                    $resource = call_user_func($callback, $resource);
                    break;
                }            
            }
            
            if(!$resource instanceof Emerald_Acl_ResourceInterface) {
                throw new Zend_Acl_Exception("Could not autoload Acl resource '{$origResource}'");
            }
        }

        if(!$this->has($resource)) {
            $resource->autoloadAclResource($this);
            $this->cacheSave();
        }

        return $resource;
    }


    /**
     * Autoloads role
     * @param unknown_type $role
     * @throws Zend_Acl_Exception
     * @returns Emerald_Acl_RoleInterface
     */
    public function autoloadRole($role)
    {
        if(!$role instanceof Emerald_Acl_RoleInterface) {
            throw new Zend_Acl_Exception("Can not autoload role '{$role}'");
        }
        
        $origRole = $role;
        foreach($this->_roleAutoloaders as $regex => $callback) {
            if(preg_match($regex, $role)) {
                $role = call_user_func($callback, $role);
                break;
            }            
        }
        
        if(!$role instanceof Emerald_Acl_RoleInterface) {
            throw new Zend_Acl_Exception("Could not autoload Acl role '{$origResource}'");
        }
        
        if(!$this->hasRole($role)) {
            $role->autoloadAclRole($this);
            $this->cacheSave();
        }
        
        return $role;
        
    }
    
    /**
     * Returns cache
     * 
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return $this->_cache;
    }
    
    
    /**
     * Sets cache
     * 
     * @param Zend_Cache_Core $cache
     */
    public function setCache(Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
    }

    
    /**
     * Retrieves and initializes acl from cache
     * 
     * @param Zend_Cache_Core $cache
     * @return Emerald_Acl|false Cache on success
     */
    public static function cacheLoad(Zend_Cache_Core $cache)
    {
        $acl = $cache->load('Emerald_Acl');
        if($acl) {
            $acl->setcache($cache);
        }
        return $acl;
    } 
    

    /**
     * Saves the cache
     */
    public function cacheSave()
    {
        $this->getCache()->save($this, 'Emerald_Acl');
    }


    /**
     * Flushes the cache
     */
    public function cacheRemove()
    {
        $this->getCache()->remove('Emerald_Acl');
    }

    
    public function addResource($resource, $parent = null)
    {
        if($parent && !$this->has($parent)) {
            $parent = $this->autoloadResource($parent);
        }
         
        return parent::addResource($resource, $parent);
         
         
    }


    public function isAllowed($role = null, $resource = null, $privilege = null)
    {

        if(!$this->hasRole($role)) {
            $this->autoloadRole($role);
        }

        if(!$this->has($resource)) {
            $resource = $this->autoloadResource($resource);
        }

        return parent::isAllowed($role, $resource, $privilege);

    }


    public function remove($resource)
    {
        parent::remove($resource);
        $this->cacheSave();
    }


    public function removeRole($role)
    {
        parent::removeRole($role);
        $this->cacheSave();
    }
    
    
    public function removeAll()
    {
        parent::removeAll();
        $this->cacheSave();
    }

    
    public function removeRoleAll()
    {
        parent::removeRoleAll();
        $this->cacheSave();
    }

    





}

?>