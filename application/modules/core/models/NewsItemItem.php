<?php
class Core_Model_NewsItemItem extends Emerald_Model_AbstractItem
{
	public function getChannel()
	{
		$model = new Core_Model_NewsChannel();
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
	
	
}