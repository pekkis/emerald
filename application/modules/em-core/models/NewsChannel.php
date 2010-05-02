<?php
class EmCore_Model_NewsChannel extends Emerald_Model_Cacheable
{
	
	
	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new EmCore_Model_DbTable_NewsChannel();
		}
		return $table;
	}
	
	
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
			$ret = ($row) ? new EmCore_Model_NewsChannelItem($row->toArray()) : false;
			
			$this->storeCached($id, $ret);
		}
		
		return $ret;
		
	}
	
	
	/**
	 * Finds item with page id
	 * 
	 * @param $pageId
	 * @return EmCore_Model_NewsChannelItem
	 */
	public function findByPageId($pageId)
	{
		if($id = $this->findCached('page_' . $pageId)) {
			return $this->find($id);
		}
		
		$tbl = $this->getTable();
		$id = $tbl->getAdapter()->fetchOne("SELECT id FROM emerald_news_channel WHERE page_id = ?", array($pageId));
		
		if($id) {
			$this->storeCached('page_' . $pageId, $id);
			return $this->find($id); 
		} else {

			$pageModel = new EmCore_Model_Page();
			$page = $pageModel->find($pageId);
			
			$item = new EmCore_Model_NewsChannelItem(
				array(
					'id' => null,
					'page_id' => $page->id,
					'title' => Zend_Registry::get('Zend_Translate')->translate('News', $page->getLocale()), 
					'link_readmore' => Zend_Registry::get('Zend_Translate')->translate('Read more', $page->getLocale())
				)
			);
			$this->save($item);
			return $item;
		}
			
		
	}
	
	
	public function save(EmCore_Model_NewsChannelItem $channel, array $permissions = array())
	{
		if(!is_numeric($channel->id)) {
			$channel->id = null;
		}
		
		$tbl = $this->getTable();
				
		if(!$channel->id || !$row = $tbl->find($channel->id)->current()) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($channel->toArray());
		$row->save();
		
		$channel->setFromArray($row->toArray());
		
		$this->storeCached($channel->id, $channel);
		$this->storeCached('page_' . $channel->page_id, $channel->id);
				
	}
	
	
	
	public function getItems(EmCore_Model_NewsChannelItem $channel, $invalids = false)
	{
		if(!$ids = $this->findCached('items_' . $channel->id)) {
			$db = $this->getTable()->getAdapter();
			$ids = $db->fetchCol("SELECT id FROM emerald_news_item WHERE news_channel_id = ? ORDER BY valid_start DESC", array($channel->id));
			$this->storeCached('items_' . $channel->id, $ids);
		}

		
		$itemModel = new EmCore_Model_NewsItem();
		$iter = new ArrayIterator();
		
		foreach($ids as $id) {
			$iter->append($itemModel->find($id));
		}
		
		if($invalids == false) {
			$iter2 = new EmCore_Model_NewsItemValidityFilterIterator($iter);
			
			$iter = new ArrayIterator();
			foreach($iter2 as $item) {
				$iter->append($item);
			}
		}
		
		
		$adapter = new Zend_Paginator_Adapter_Iterator($iter);
		$paginator = new Zend_Paginator($adapter);
		$paginator->setItemCountPerPage($channel->items_per_page);
		
		return $paginator;
		
		
		
	}
	
	
	
}
?>