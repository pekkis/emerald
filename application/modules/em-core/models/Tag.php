<?php
class EmCore_Model_Tag extends Emerald_Model_Cacheable
{
	protected static $_table = 'EmCore_Model_DbTable_Tag'; 
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return EmCore_Model_NewsChannelItem
	 */
	public function find($id)
	{
		if(!$ret = $this->findCached($id)) {
			$tbl = $this->getTable();
			$row = $tbl->find($id)->current();
			$ret = ($row) ? new EmCore_Model_TagItem($row->toArray()) : false;
			$this->storeCached($id, $ret);
			
			if($ret) {
				$this->storeCached('name_' . $ret->name, $ret);
			}
			
		}
		return $ret;
	}
	
	
	public function findByName($name)
	{
		if($id = $this->findCached('name_' . $name)) {
			return $this->find($id);
		}
				
		$tbl = $this->getTable();
		$id = $tbl->getAdapter()->fetchOne("SELECT id FROM emerald_tag WHERE name = ?", array($name));
		if(!$id) {
			$tag = new EmCore_Model_TagItem();
			$tag->name = $name;
			$this->save($tag);
			$this->storeCached('name_' . $tag->name, $tag->id);
			return $tag;
		}
		$this->storeCached('name_' . $name, $id);
		return $this->find($id);
	}
	
	
	public function save(EmCore_Model_TagItem $item)
	{
		if(!is_numeric($item->id)) {
			$item->id = null;
		}
		$tbl = $this->getTable();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($item->toArray());
		$row->save();
		
		$item->setFromArray($row->toArray());
		
		$this->storeCached($item->id, $item);
				
	}
	
	public function delete(EmCore_Model_TagItem $item)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
		$this->clearCached($item->id);
	}
	
	
}
?>