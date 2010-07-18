<?php
class EmCore_Model_Taggable extends Emerald_Model_Cacheable
{
	protected static $_table = 'EmCore_Model_DbTable_Taggable';

	
	
	
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
			$ret = ($row) ? new EmCore_Model_TaggableItem($row->toArray()) : false;
			
			if($ret) {
				
				$sel = $this->getTable()->getAdapter()->select();
				
				$sel
					->from("emerald_tag", "name")
					->join("emerald_taggable_tag", "emerald_tag.id = emerald_taggable_tag.tag_id", null)
					->where("emerald_taggable_tag.taggable_id = ?", $id);
				
				$res = $this->getTable()->getAdapter()->fetchCol($sel, array(), Zend_Db::FETCH_ASSOC);
				
				sort($res, SORT_LOCALE_STRING);
												
				$ret->tags = $res;
			}
			
			
			$this->storeCached($id, $ret);
		}
		return $ret;
	}
	
	
	
	public function getForm()
	{
		$form = new EmCore_Form_Tags();
		$form->setModel($this);
		
		return $form;
	}
	
	
	
	public function findFor(Emerald_Model_TaggableItemInterface $item)
	{
		$id = $item->getTaggableId();		
		if($id) {
			return $this->find($id);
		}
	}
	
	
	public function registerFor(Emerald_Model_TaggableItemInterface $item)
	{
		if($item->getTaggableId()) {
			return $this->findFor($item);
		}
				
		$taggable = new EmCore_Model_TaggableItem();			
		$taggable->type = $item->getType();
		
		$this->save($taggable);
		$item->setTaggableId($taggable->id);
		
		
	}
	
	
	
	
	public function save(EmCore_Model_TaggableItem $item)
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
		
		$tagModel = new EmCore_Model_Tag();
		$tagObjs = array();

		if($item->tags) {
			foreach($item->tags as $tag) {
				$tagObjs[] = $tagModel->findByName($tag);
			}
			
		}
		
		$ttTbl = new EmCore_Model_DbTable_Taggable_Tag();
		$ttTbl->delete($ttTbl->getAdapter()->quoteInto('taggable_id = ?', $item->id));
		
		foreach($tagObjs as $tagObj) {
			$ttTbl->insert(array('taggable_id' => $item->id, 'tag_id' => $tagObj->id));
		}
		
		
		
		$this->storeCached($item->id, $item);
				
	}
	
	public function delete(EmCore_Model_TaggableItem $item)
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