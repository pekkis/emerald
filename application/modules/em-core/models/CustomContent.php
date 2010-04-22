<?php
class EmCore_Model_CustomContent
{
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new EmCore_Model_DbTable_CustomContent();
		}
		return $table;
	}
	
	
	public function find($customContentId, $blockId)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($customContentId, $blockId)->current();
		return ($row) ? new EmCore_Model_CustomContentItem($row->toArray()) : new EmCore_Model_CustomContentItem(array('page_id' => $customContentId, 'block_id' => $blockId));
	}
		
	
	
	public function save(EmCore_Model_CustomContentItem $customContent)
	{
		$tbl = $this->getTable();
		
		$row = $tbl->find($customContent->page_id, $customContent->block_id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
				
		$row->setFromArray($customContent->toArray());
		$row->save();
		
		die('xooxer');

	}
	
	
	
}