<?php
class EmCore_Model_LocaleItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Resource_Interface
{
	
	private $_optionContainer;
	
	private $_options = array();
	
	
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
			$this->_optionContainer->setTable(new EmCore_Model_DbTable_Locale_Option);
			$this->_optionContainer->setWhereConditions(array('locale_locale' => $this->locale));
			
		}
		return $this->_optionContainer;
	}
	
	
	
    public function getOptionCache()
    {
    	if(!$this->_optionCache) {
    		$this->_optionCache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
    	}
    	return $this->_optionCache;
    }
    
    
    public function getOption($key)
    {
		$options = $this->getOptions();
		return (isset($options[$key])) ? $options[$key] : false;
    }
    
    
    public function setOption($key, $value)
    {
    	$this->_options[$key] = $value;
    	$this->_getOptionContainer()->$key = $value;
    	$this->getOptionCache()->remove('locale_options_' . $this->locale);
    		
    }
    
    public function getOptions()
    {
    	if(!$this->_options) {
    		if(!$this->_options = $this->getOptionCache()->load('locale_options_' . $this->locale)) {
    			$this->_options = $this->_getOptionContainer()->getOptions();
    			$this->getOptionCache()->save($this->_options, 'locale_options_' . $this->locale); 
    		}
    	}
    	return $this->_options;
    }
	    
	
    public function autoloadAclResource(Zend_Acl $acl)
	{
		if(!$acl->has($this)) {
			$acl->add($this);
			$model = new EmCore_Model_DbTable_Permission_Locale_Ugroup();
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