<?php
class Core_Model_NewsChannel
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
			$table = new Core_Model_DbTable_NewsChannel();
		}
		return $table;
	}
	
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return Core_Model_NewsChannelItem
	 */
	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new Core_Model_NewsChannelItem($row->toArray()) : false;
	}
	
	
	/**
	 * Finds item with page id
	 * 
	 * @param $pageId
	 * @return Core_Model_NewsChannelItem
	 */
	public function findByPageId($pageId)
	{
		$tbl = $this->getTable();
		$row = $tbl->fetchRow(array('page_id = ?' => $pageId));
		
		if($row) {
			$item = new Core_Model_NewsChannelItem($row->toArray());
		} else {

			$pageModel = new Core_Model_Page();
			$page = $pageModel->find($pageId);
						
			$item = new Core_Model_NewsChannelItem(
				array(
					'id' => null,
					'page_id' => $pageId,
					'title' => Zend_Registry::get('Zend_Translate')->translate('News', $page->getLocale()), 
					'link_readmore' => Zend_Registry::get('Zend_Translate')->translate('Read more', $page->getLocale())
				)
			);
			
			$this->save($item);
			
		}

		
		return $item;
		
		
		
		
	}
	
	
	public function save(Core_Model_NewsChannelItem $channel, array $permissions = array())
	{
		if(!is_numeric($channel->id)) {
			$channel->id = null;
		}
		
		$tbl = $this->getTable();
				
		if(!$channel->id || !$row = $tbl->find($channel->id)->current()) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($channel->toArray());
		$row->save();
		
		$channel->setFromArray($row->toArray());
				
	}
	
	
	
}
?>