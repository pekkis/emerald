<?php
class Emerald_Db_Table_Row_Htmlcontent extends Zend_Db_Table_Row_Abstract 
{
	
	/**
	 * Returns objects page.
	 *
	 * @return Emerald_Page
	 */
	public function getPage()
	{
		$pageTbl = Emerald_Model::get('DbTable_Page');
		return $pageTbl->find($this->page_id)->current();
	}
}