<?php
class Emerald_Filelib_FolderIterator implements RecursiveIterator, Countable
{
	private $_children;
	
	
	/**
	 * Items
	 *
	 * @var Zend_Db_Table_Rowset_Abstract
	 */
	private $_items;
	
	private $_filelib;
	
	public function __construct($filelib, $parentId)
	{
		$this->_filelib = $filelib;				
			
		$folderTbl = new Emerald_Filelib_DbTable_Folder($this->_filelib->getDb());
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
		if(!$this->_children) {
			$this->_children = new self($this->_filelib, $this->current()->id);
			return (bool) $this->_children->count();
		}
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