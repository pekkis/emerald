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

    static public function initialize(Zend_Acl $acl, Emerald_Application_Customer $customer)
    {


        $anonGroup = 'Emerald_Group_' . EmCore_Model_Group::GROUP_ANONYMOUS;
        $acl->addRole($anonGroup);
        $acl->deny($anonGroup);

        $rootGroup = 'Emerald_Group_' . EmCore_Model_Group::GROUP_ROOT;
        $acl->addRole($rootGroup);
        $acl->allow($rootGroup);

        return $acl;

    }


    public function autoloadResource($resource)
    {

        // @todo This is a kludge... Must be rethinked to be extensible and stuff. But not in 3.0 series :)
        if(!$resource instanceof Emerald_Acl_Resource_Interface) {
            if(preg_match("/^Emerald_Page/", $resource)) {
                $split = explode("_", $resource, 3);
                $pageModel = new EmCore_Model_Page();
                $resource = $pageModel->find($split[2]);
            } else if(preg_match("/^Emerald_Locale/", $resource)) {
                $split = explode("_", $resource, 3);
                $localeModel = new EmCore_Model_Locale();
                $resource = $localeModel->find($split[2]);
            } else if(preg_match("/^Emerald_Activity/", $resource)) {
                $split = explode("_", $resource, 3);
                $activityModel = new EmAdmin_Model_Activity();
                $splitted = explode("___", $split[2]);
                $resource = $activityModel->findByCategoryAndName($splitted[0], $splitted[1]);
            } else {
                throw new Zend_Acl_Exception("Can not autoload resource '{$resource}'");
            }
        }

        if(!$this->has($resource)) {
            $resource->autoloadAclResource($this);
            $this->cacheSave();
        }

        return $resource;
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
            if(!$role instanceof Emerald_Acl_Role_Interface) {
                throw new Zend_Acl_Exception("Can not autoload role '{$role}'");
            }
            $role->autoloadAclRole($this);
            $this->cacheSave();
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




    public function cacheSave()
    {
        Zend_Registry::get('Emerald_CacheManager')->getCache('default')->save($this, 'acl');
    }



    public function cacheRemove()
    {
        Zend_Registry::get('Emerald_CacheManager')->getCache('default')->remove('acl');
    }






}

?>