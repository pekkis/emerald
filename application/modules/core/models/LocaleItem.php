<?php
class Core_Model_LocaleItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Resource_Interface
{
	
	private $_optionContainer;
	
	
	public function getResourceId()
	{
		return 'Emerald_Locale_' . $this->locale;
	}
	
	
	
	public function __toString()
	{
		return $this->locale;
	}
	
	
	
	
	protected function _getOptionContainer()
	{
		if(!$this->_optionContainer) {
			$this->_optionContainer = new Emerald_Db_OptionContainer($this);
			$this->_optionContainer->setTable(new Core_Model_DbTable_Locale_Option);
			$this->_optionContainer->setWhereConditions(array('locale_locale' => $this->locale));
			
		}
		return $this->_optionContainer;
	}
	
	
	
	public function getOption($key, $default = null)
    {
    	return $this->_getOptionContainer()->get($key, $default);
    }
    
    
    public function setOption($key, $value)
    {
    	return $this->_getOptionContainer()->set($key, $value);
    }
	
	
    
    public function getOptions()
    {
    	return $this->_getOptionContainer()->getOptions();
    }
    
	
    public function autoloadAclResource(Zend_Acl $acl)
	{
		if(!$acl->has($this)) {
			$acl->add($this);
			$model = new Core_Model_DbTable_Permission_Locale_Ugroup();
	       	$sql = "SELECT ugroup_id, permission FROM emerald_permission_locale_ugroup WHERE locale_locale = ?";
	       	$res = $model->getAdapter()->fetchAll($sql, $this->locale);
	       	foreach($res as $row) {
	       		foreach(Emerald_Permission::getAll() as $key => $name) {
	       			if($key & $row->permission) {
	       				$role = "Emerald_Group_{$row->ugroup_id}";
	       				if($acl->hasRole($role)) {
	       					$acl->allow($role, $this, $name);	
	       				}
	       			}
	       		}
	       	}
	       	
		}		
	}
	
    
    
}