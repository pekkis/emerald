<?php
class EmCore_Model_FormContent
{
	
	
	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new EmCore_Model_DbTable_FormContent();
		}
		return $table;
	}
	
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return EmCore_Model_FormContentItem
	 */
	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new EmCore_Model_FormContentItem($row->toArray()) : false;
	}
	
	
	/**
	 * Finds item with page id
	 * 
	 * @param $pageId
	 * @return EmCore_Model_FormContentItem
	 */
	public function findByPageId($pageId)
	{
		$tbl = $this->getTable();
		$row = $tbl->fetchRow(array('page_id = ?' => $pageId));
		
		if($row) {
			$item = new EmCore_Model_FormContentItem($row->toArray());
		} else {

			$pageModel = new EmCore_Model_Page();
			$page = $pageModel->find($pageId);
						
			$item = new EmCore_Model_FormContentItem(
				array(
					'id' => null,
					'page_id' => $pageId,
				)
			);
			
			// $this->save($item);
			
		}

		
		return $item;
		
		
		
		
	}
	
	
	public function save(EmCore_Model_FormContentItem $item)
	{
		if(!is_numeric($item->page_id)) {
			$item->page_id = null;
		}
		
		$tbl = $this->getTable();
		
		$row = $tbl->find($item->page_id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($item->toArray());
		$row->save();
		
		$item->setFromArray($row->toArray());
				
	}
	
	
	
}
?>