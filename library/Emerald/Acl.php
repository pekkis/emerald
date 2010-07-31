<?php
/**
 * Emerald Acl helper class... temporarily here.
 *
 * @TODO: OPTIMIZE!
 * @TODO: CACHETIZE!
 * @TODO: TERRORIZE!
 *
 */
class Emerald_Acl extends Zend_Acl
{

    private $_cache;
    
    private $_resourceAutoloaders = array();

    private $_roleAutoloaders = array();
    
    public function addResourceAutoloader($regex, $callback)
    {
        if(!is_callable($callback)) {
            throw new Emerald_Exception("Acl autoloader callback is not callable");
        }
        
        if(!is_array($regex)) {
            $regex = array($regex);
        }
        
        foreach($regex as $regx) {
            $this->_resourceAutoloaders[$regx] = $callback;    
        }
    }

    
    public function removeResourceAutoloader($regex)
    {
        if(isset($this->_resourceAutoloaders[$regex])) {
            unset($this->_resourceAutoloaders[$regex]);
        }
    }
    
    public function addRoleAutoloader($regex, $callback)
    {
        if(!is_callable($callback)) {
            throw new Emerald_Exception("Acl role autoloader callback is not callable");
        }
        
        if(!is_array($regex)) {
            $regex = array($regex);
        }
        
        foreach($regex as $regx) {
            $this->_roleAutoloaders[$regx] = $callback;    
        }
        
    }
    
    
    public function removeRoleAutoloader($regex)
    {
        if(isset($this->_roleAutoloaders[$regex])) {
            unset($this->_roleAutoloaders[$regex]);
        }
    }
    
    
    public function autoloadResource($resource)
    {
        if(!$resource instanceof Emerald_Acl_Resource_Interface) {
            $origResource = $resource;
            foreach($this->_resourceAutoloaders as $regex => $callback) {
                if(preg_match($regex, $resource)) {
                    $resource = call_user_func($callback, $resource);
                    break;
                }            
            }
            
            if(!$resource instanceof Emerald_Acl_Resource_Interface) {
                throw new Emerald_Exception("Could not autoload Acl resource '{$origResource}'");
            }
        }

        if(!$this->has($resource)) {
            $resource->autoloadAclResource($this);
            $this->cacheSave();
        }

        return $resource;
    }


    public function autoloadRole($role)
    {
        if(!$role instanceof Emerald_Acl_Role_Interface) {
            throw new Zend_Acl_Exception("Can not autoload role '{$role}'");
        }
        
        $origRole = $role;
        foreach($this->_roleAutoloaders as $regex => $callback) {
            if(preg_match($regex, $role)) {
                $role = call_user_func($callback, $role);
                break;
            }            
        }
        
        if(!$role instanceof Emerald_Acl_Role_Interface) {
            throw new Emerald_Exception("Could not autoload Acl role '{$origResource}'");
        }
        
        if(!$this->hasRole($role)) {
            $role->autoloadAclRole($this);
            $this->cacheSave();
        }
        
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

    
    public function getCache()
    {
        return $this->_cache;
    }
    
    
    public function setCache(Zend_Cache_Core $cache)
    {
        $this->_cache = $cache;
    }

    
    public static function cacheLoad(Zend_Cache_Core $cache)
    {
        $acl = $cache->load('Emerald_Acl');
        if($acl) {
            $acl->setcache($cache);
        }
        return $acl;
    } 
    

    public function cacheSave()
    {
        $this->getCache()->save($this, 'Emerald_Acl');
    }


    public function cacheRemove()
    {
        $this->getCache()->remove('Emerald_Acl');
    }






}

?>