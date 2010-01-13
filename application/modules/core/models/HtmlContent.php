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
	
	
	public function find($htmlContentId, $blockId)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($htmlContentId, $blockId)->current();
		return ($row) ? new Core_Model_HtmlContentItem($row->toArray()) : new Core_Model_HtmlContentItem(array('page_id' => $htmlContentId, 'block_id' => $blockId, 'content' => ''));
	}
		
	
	
	public function save(Core_Model_HtmlContentItem $htmlContent)
	{
		$tbl = $this->getTable();
		
		$row = $tbl->find($htmlContent->page_id, $htmlContent->block_id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
		
		Zend_Debug::dump($htmlContent->toArray());
		
		$row->setFromArray($htmlContent->toArray());
		$row->save();

	}
	
	
	
}