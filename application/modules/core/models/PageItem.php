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
	
	
	
}