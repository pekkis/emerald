<?php
class Core_Model_NewsChannelItem extends Emerald_Model_AbstractItem
{

		
	
	
	protected $_paginator;
	
	public function getItemPaginator()
	{
	}
	
	
	
	public function getItems()
	{
		if(!$this->_paginator) {
			$adapter = new Core_Model_Paginator_Adapter_NewsItem($this);
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage($this->items_per_page);
			$this->_paginator = $paginator;
		}
		
		return $this->_paginator;
	}
	
	
	
}