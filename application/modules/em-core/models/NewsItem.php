<?php
class EmCore_Model_NewsItem extends Emerald_Model_Cacheable
{
	protected static $_table = 'EmCore_Model_DbTable_NewsItem'; 
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return EmCore_Model_NewsItemItem
	 */
	public function find($id)
	{
		if(!$ret = $this->findCached($id)) {
			$tbl = $this->getTable();
			$row = $tbl->find($id)->current();
			$ret =($row) ? new EmCore_Model_NewsItemItem($row->toArray()) : false;
			
			if($ret) {
				$this->storeCached($id, $ret);
			}
		}
		
		return $ret;
	}
	
	
	
	
	public function save(EmCore_Model_NewsItemItem $item)
	{
		if(!is_numeric($item->id)) {
			$item->id = null;
		}
		
		$tbl = $this->getTable();
				
		if(!$item->id || !$row = $tbl->find($item->id)->current()) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($item->toArray());
		$row->save();
		
		$item->setFromArray($row->toArray());
		
		$this->storeCached($item->id, $item);
		$channelModel = new EmCore_Model_NewsChannel();
		$channelModel->clearCached('items_' . $item->news_channel_id);
		
	}
	
	
		
	public function delete(EmCore_Model_NewsItemItem $item)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
		
		$this->clearCached($item->id);
		$channelModel = new EmCore_Model_NewsChannel();
		$channelModel->clearCached('items_' . $item->news_channel_id);
		
	}
	
	
}
?>