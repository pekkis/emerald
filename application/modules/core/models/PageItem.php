<?php
class Core_Model_PageItem extends Emerald_Model_AbstractItem implements Zend_Acl_Resource_Interface
{

	public function __toString()
	{
		return $this->id;
	}
	
	
	public function getResourceId()
	{
		return 'Emerald_Page_' . $this->id;
	}
	
	
	
	public function getLocaleItem()
	{
		$localeModel = new Core_Model_Locale();
		return $localeModel->find($this->locale);
	}
	
	
	
	public function getLayoutObject($action = null)
	{
		require Zend_Registry::get('Emerald_Customer')->getRoot() . '/views/scripts/layouts/Default.php';				
		$tpl = new Emerald_Layout_Default();
		
		if($action) {
			$tpl->setAction($action);
		}
				
		return $tpl;
	}
	
	
	
	
	public function __lazyLoadAclResource(Emerald_Acl $acl)
	{
		if(!$acl->has($this)) {
			$acl->add($this);
			$model = new Core_Model_DbTable_Permission_Page_Ugroup();
	       	$sql = "SELECT ugroup_id, permission FROM permission_page_ugroup WHERE page_id = ?";
	       	$res = $model->getAdapter()->fetchAll($sql, $this->id);
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