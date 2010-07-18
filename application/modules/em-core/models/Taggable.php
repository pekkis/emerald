<?php
class EmCore_Model_Taggable extends Emerald_Model_Cacheable
{
	protected static $_table = 'EmCore_Model_DbTable_Taggable';

	private $_taggerModel;
	
	protected function _getRawDependencies()
	{
		return parent::_getRawDependencies() + array('router' => function() { return Zend_Controller_Front::getInstance()->getRouter(); });
	}
		
	
		
	public function getTaggerModel(EmCore_Model_TaggableItem $taggable)
	{
		if(!isset($this->_taggerModel[$taggable->type])) {
			$model = new $taggable->type;
			if(!$model instanceof Emerald_Model_TaggerModelInterface) {
				throw new Emerald_Model_Exception(get_class($model) . " does not implement TaggerModelInterface");
			}
			$this->_taggerModel[$taggable->type] = $model;
			
		}
		return $this->_taggerModel[$taggable->type];
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
	
	
	
	public function findAll($tag = null)
	{
		$sel = $this->getTable()->getAdapter()->select();
		$sel
			->from("emerald_taggable", array("id", "type"))
			->order("id DESC");
			
		if($tag) {
			
			$tagModel = new EmCore_Model_Tag();
			$tag = $tagModel->findByName($tag);
			
			$sel
				->join("emerald_taggable_tag", "emerald_taggable.id = emerald_taggable_tag.taggable_id", null)
				->where("emerald_taggable_tag.tag_id = ?", $tag->id);
		}
			

		$ids = $this->getTable()->getAdapter()->fetchCol($sel);
		
		$taggables = array();
		foreach($ids as $id) {
			$taggable = $this->find($id);
			$taggables[] = $taggable;
		}
		
		return $taggables;
	}
	
	
	public function getTagCloud($pageId)
	{
		$taggables = $this->findAll();
		
		$tags = array();
		
		foreach($taggables as $taggable) {
			if($tagger = $this->findTagger($taggable)) {
				foreach($taggable->tags as $tag) {
					if(!isset($tags[$tag])) {
						$tags[$tag] = 1;
					} else {
						$tags[$tag]++;
					}
				}
			}
		}
		
		ksort($tags, SORT_LOCALE_STRING);
		
		$tagItems = array();

		// $url = $channel->getPage();
		
		$router = $this->getRouter();
		
		foreach($tags as $title => $weight) {
			$tagItem = new Zend_Tag_Item(array('title' => $title, 'weight' => $weight));

			$url = $router->assemble(array(
				'tag' => $title,
			), "page_{$pageId}_tag-cloud_tag");		
			
			$tagItem->setParam('url', $url);
						
			$tagItems[] = $tagItem;
		}
		
		$cloud = new Zend_Tag_Cloud(array('tags' => $tagItems));
		
		return $cloud;
		
	}
	
	
	public function findDescriptorsFor($tag)
	{
		$taggables = $this->findAll($tag);
		$descriptions = array();
		foreach($taggables as $taggable) {
			if($description = $this->findDescriptor($taggable)) {
				$descriptions[] = $description;
			}
		}
		return new ArrayIterator($descriptions);
	}
	
	
	
	public function findTagger(EmCore_Model_TaggableItem $taggable)
	{
		try {
			$ret = $this->getTaggerModel($taggable)->findTagger($taggable);
			
			// @todo: refactor: this is just a quick'n dirty obsolete taggable cleaner
			if(!$ret) {
				$this->delete($taggable);
			}
			
			return $ret;
			
			
		} catch(Exception $e) {
			throw $e;
		}	
		
		
	}
	
	
	public function findDescriptor(EmCore_Model_TaggableItem $taggable)
	{
		if(!$taggerModel = $this->getTaggerModel($taggable)) {
			return false;
		}
		
		$ret = $taggerModel->findDescriptor($taggable);
		
		// @todo: refactor: this is just a quick'n dirty obsolete taggable cleaner
		if(!$ret) {
			$this->delete($taggable);
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
		
		return $taggable;
		
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
		$this->clearCached('all_taggables');
				
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
		$this->clearCached('all_taggables');
		
		
	}
	
	
	
}
?>