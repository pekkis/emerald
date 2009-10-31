<?php
class Emerald_Sitemap_Node implements Zend_Acl_Resource_Interface 
{
	private $_row;

	public function __construct($row)
	{
		$this->_row = $row;
		
		Emerald_Page::find($row->id);
		
	}
	
	public function __get($key)
	{
		return $this->_row->$key;
	}
	
	
	public function __set($key, $value)
	{
		$this->_row->$key = $value;
	}
	
	public function toArray()
	{
		return (Array) $this->_row;
	}
	
	
	public function getResourceId()
	{
		return 'Emerald_Page_' . $this->_row->id;
	}
	
	
		
}