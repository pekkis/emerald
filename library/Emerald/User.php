<?php
class Emerald_User extends Zend_Db_Table_Row_Abstract implements Zend_Acl_Role_Interface
{
	const USER_ANONYMOUS = 1;
		
	private $_groups;
	
	private $_options;
		
	public function __construct($config = array())
	{
		parent::__construct($config);
		
	    if (isset($config['data']) && isset($config['stored']) && $config['stored'] === true) {
	    	$this->_options = new Emerald_Options_User($this); 	    	
        }
				
	}
	
	
	public function init()
	{
				
		$acl = Zend_Registry::get('Emerald_Acl');
		
		if(!$acl->hasRole($this)) {
			$gruppen = array();
			$groupz = $this->findGroups();
	       	foreach($groupz as $group) {
	       		$gruppen[] = $group;
	       	}
	       	
	       	$acl->addRole($this, $gruppen);
		}
	}
	
	
	
	public function getRoleId()
	{
		return 'Emerald_User_' . $this->id;
	}
	
	public function inGroup($groupId)
	{
		foreach($this->findGroups() as $group)
			if($group->id == $groupId) return true;
		
		return false;
		
	}
	
	
	public function findGroups()
	{
		if(!$this->_groups)
			$this->_groups = $this->findManyToManyRowset('Core_Model_Group', 'Core_Model_UserGroup');
		return $this->_groups;
	}
	
	
	public function getOption($key, $default = null)
    {
    	if(!$this->_options)
    		return $default;
    	
    	return $this->_options->get($key, $default);
    }
    
    
    public function setOption($key, $value)
    {
    	if(!$this->_options || $this->id == Emerald_User::USER_ANONYMOUS)
    		return false;
    	
    	return $this->_options->set($key, $value);
    }
	 
	
	
}
?>