<?php
class Core_Model_Paginator_Adapter_NewsItem implements Zend_Paginator_Adapter_Interface
{
	protected $_channel;
	
	protected $_innerAdapter;

	
	public function __construct(Core_Model_NewsChannelItem $channel)
	{
		$this->_channel = $channel;
	}
	
	
	
	public function getInnerAdapter()
	{
		if(!$this->_innerAdapter) {
			$tbl = new Core_Model_DbTable_NewsItem();
			$sel = $tbl->select()->where('news_channel_id = ?', $this->_channel->id);
			$this->_innerAdapter = new Zend_Paginator_Adapter_DbTableSelect($sel);
		}
		
		return $this->_innerAdapter;		
	}
	
	
	public function count()
	{
		return $this->getInnerAdapter()->count();
	}
	
	
	
	public function getItems($offset, $itemCountPerPage)
	{
		$rawItems = $this->getInnerAdapter()->getItems($offset, $itemCountPerPage);
		
		$items = new ArrayIterator();
		foreach($rawItems as $item) {
			$items[] = new Core_Model_NewsItem($item->toArray());
		}
		return $items;		
	}
	
	
	
}