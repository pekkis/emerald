<?php
class Emerald_Application_Resource_Emacl extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        $options = $this->getOptions();

        $this->getBootstrap()->bootstrap('customer')->bootstrap('emdb')->bootstrap('emuser')->bootstrap('filelib');

        $customer = $this->getBootstrap()->getResource('customer');

        $cache = Zend_Registry::get('Emerald_CacheManager')->getCache($options['cache']);
        if(!$acl = Emerald_Acl::cacheLoad($cache)) {
            
            // Failed to load from cache, initialize from scratch.
            
            $acl = new Emerald_Acl();
            
            // Add default groups
            $anonGroup = 'Emerald_Group_' . EmCore_Model_Group::GROUP_ANONYMOUS;
            $acl->addRole($anonGroup);
            $acl->deny($anonGroup);

            $rootGroup = 'Emerald_Group_' . EmCore_Model_Group::GROUP_ROOT;
            $acl->addRole($rootGroup);
            $acl->allow($rootGroup);
                        
            $cache->save($acl, 'acl');
            $acl->setCache($cache);
        }
        
        // Register Emerald's autoloading resources
        $autoloader = new Emerald_Acl_Autoloader();
        $acl->addResourceAutoloader(
            array(
                "/^Emerald_Page/",
                "/^Emerald_Locale/",
                "/^Emerald_Activity/",
            ),
            array($autoloader, 'autoloadResource')
        );
        
        // If defined, initialize filelib.
        if($this->_options['initFilelib'] == true) {
            
            $filelib = $this->getBootstrap()->getResource('filelib');
            $user = $this->getBootstrap()->getResource('emuser');
            
            $aclHandler = new Emerald_Filelib_Acl_Zend();
            $aclHandler->setAcl($acl);
            $aclHandler->setAnonymousRole("Emerald_Group_" . EmCore_Model_Group::GROUP_ANONYMOUS);
            	
            $aclHandler->setRole($user);
            	
            $filelib->setAcl($aclHandler);
            $filelib->setFileItemClass("EmCore_Model_FileItem");
            $filelib->setFolderItemClass("EmCore_Model_FolderItem");
        }

        Zend_Registry::set('Emerald_Acl', $acl);

        return $acl;


    }

}