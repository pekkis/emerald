<?php
class EmCore_Model_HtmlContent extends Emerald_Model_Cacheable
{
	protected static $_table = 'EmCore_Model_DbTable_HtmlContent'; 
	
	public function find($htmlContentId, $blockId)
	{
		if(!$ret = $this->findCached(array($htmlContentId, $blockId))) {
			$tbl = $this->getTable();
			$row = $tbl->find($htmlContentId, $blockId)->current();
			
			$ret = ($row) ? new EmCore_Model_HtmlContentItem($row->toArray()) : new EmCore_Model_HtmlContentItem(array('page_id' => $htmlContentId, 'block_id' => $blockId, 'content' => ''));
			if($ret) {
				$this->storeCached(array($ret->page_id, $ret->block_id), $ret);
			}
		}
		
		return $ret;
	}
		
	
	
	public function save(EmCore_Model_HtmlContentItem $htmlContent)
	{
		$tbl = $this->getTable();
		
		$row = $tbl->find($htmlContent->page_id, $htmlContent->block_id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}

		$this->clearCached(array($htmlContent->page_id, $htmlContent->block_id));
		
		$row->setFromArray($htmlContent->toArray());
		$row->save();

	}
	
	
	
}