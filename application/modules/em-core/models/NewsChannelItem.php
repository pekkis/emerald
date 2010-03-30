<?php
class EmCore_Model_NewsChannelItem extends Emerald_Model_AbstractItem
{

		
	
	
	protected $_paginator;
	
	
	public function getPage()
	{
		$model = new EmCore_Model_Page();
		return $model->find($this->page_id);
	}
		
	
	public function getItems($invalids = false)
	{
		if(!$this->_paginator) {
			$adapter = new EmCore_Model_Paginator_Adapter_NewsItem($this, $invalids);
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage($this->items_per_page);
			$this->_paginator = $paginator;
		}
		
		return $this->_paginator;
	}
	
	
	
}