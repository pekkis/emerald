<?php
class Core_Model_HtmlContent
{
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new Core_Model_DbTable_HtmlContent();
		}
		return $table;
	}
	
	
	public function find($pageId, $blockId)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($pageId, $blockId)->current();
		return ($row) ? new Core_Model_LocaleItem($row->toArray()) : new Core_Model_LocaleItem(array('page_id' => $pageId, 'block_id' => $blockId, 'content' => ''));
	}
		
	
	
	
	
	
	
}