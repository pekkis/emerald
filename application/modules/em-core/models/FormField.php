<?php
class EmCore_Model_FormField extends Emerald_Model_Cacheable
{
	protected static $_table = 'EmCore_Model_DbTable_FormField'; 
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return EmCore_Model_FormFieldItem
	 */
	public function find($id)
	{
		if(!$ret = $this->findCached($id)) {
			$tbl = $this->getTable();
			$row = $tbl->find($id)->current();
			$ret = ($row) ? new EmCore_Model_FormFieldItem($row->toArray()) : false;
			if($ret) {
				$this->storeCached($ret->id, $ret);
			}
		}
		
		return $ret;
		
	}
	
	
	
	
	/**
	 * Finds all items
	 * 
	 * @return ArrayIterator
	 */
	public function findAll()
	{
		$rows = $this->getTable()->fetchAll(array(), 'name ASC');
		$iter = new ArrayIterator();
		foreach($rows as $row) {
			$iter->append(new EmCore_Model_FormFieldItem($row));
		}
		return $iter;
	}	
	
	
	public function save(EmCore_Model_FormFieldItem $item)
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
		$formModel = new EmCore_Model_Form();
		$formModel->clearCached('fields_' . $item->form_id);
		
				
	}

	
	public function delete(EmCore_Model_FormFieldItem $item)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
		
		$this->clearCached($item->id);
		$formModel = new EmCore_Model_Form();
		$formModel->clearCached('fields_' . $item->form_id);
		
		
	}
	
	
	
}
