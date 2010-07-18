<?php
class EmCore_Model_NewsItemItem extends Emerald_Model_AbstractItem implements Emerald_Model_TaggableItemInterface
{
	public function getChannel()
	{
		$model = new EmCore_Model_NewsChannel();
		return $model->find($this->news_channel_id);
	}
	
	
	public function isValid()
	{

		if(!$this->status) {
			return false;
		}
		
		$now = new DateTime();
		$validStart = new DateTime($this->valid_start);
		$validEnd = new DateTime($this->valid_end);

		if($validStart <= $now && $now <= $validEnd) {
			return true;
		}
		
		
		return false;
		
	}
	
		
	public function getTaggable()
	{
		$taggableModel = new EmCore_Model_Taggable();
		return $taggableModel->findFor($this);
	}
	
	
	
	public function getTaggableId()
	{
		return $this->taggable_id;
	}

	
	public function setTaggableId($taggableId)
	{
		$this->taggable_id = $taggableId;
	}
	
	
	
	public function getType()
	{
		return 'EmCore_Model_NewsItem';
	}	
	
	
}