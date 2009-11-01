<?php
/**
 * Emerald Acl helper class... temporarily here.
 * 
 * @TODO: OPTIMIZE!
 * @TODO: CACHETIZE!
 * @TODO: TERRORIZE!
 *
 */
class Emerald_Acl
{
	
	static public function initialize(Zend_Acl $acl)
	{

        $db = Emerald_Application::getInstance()->getDb();
        
        $anonGroup = Emerald_Model::get('Group')->find(Emerald_Group::GROUP_ANONYMOUS)->current();
        $acl->deny($anonGroup);
                
        $rootGroup = Emerald_Model::get('Group')->find(Emerald_Group::GROUP_ROOT)->current();
        $acl->allow($rootGroup);
        
        return $acl;
		
	}
	
	
	
	
	
}

?>