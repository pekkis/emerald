<?php
class Emerald_Filelib_Folder implements RecursiveIterator, Countable
{
	private $_children;
		
	
	/**
	 * Items
	 *
	 * @var Zend_Db_Table_Rowset_Abstract
	 */
	private $_items;
	
	public function __construct($parentId)
	{
		
		$folderTbl = Emerald_Model::get('Filelib_Folder');
		$expr = ($parentId) ? array('parent_id = ?' => $parentId) : array(new Zend_Db_Expr('parent_id IS NULL')); 
						
		$this->_items = $folderTbl->fetchAll($expr, array('name'));

						
	}
	
	
	public function current()
	{
		return $this->_items->current();
	}
	
	public function next()
	{
		return $this->_items->next();
	}
	
	
	public function key()
	{
		return $this->_items->key();
	}
	
	
	public function valid()
	{
		return $this->_items->valid();
	}
	
	
	public function rewind()
	{
		return $this->_items->rewind();
	}
	
	
	
	public function hasChildren()
	{
		$this->_children = new Emerald_Filelib_Folder($this->current()->id);
		return (bool) $this->_children->count();
	}
	
	
	public function getChildren()
	{
		return $this->_children;
	}
	
	
	public function count()
	{
		return $this->_items->count();
	}
	
}

?>