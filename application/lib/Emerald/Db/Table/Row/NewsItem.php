<?php
class Emerald_Db_Table_Row_NewsItem extends Zend_Db_Table_Row_Abstract 
{
	
	/**
	 * Returns item's news channel
	 *
	 * @return Emerald_Db_Table_Row_NewsChannel
	 */
	public function getChannel()
	{
		$channelTbl = Emerald_Model::get('NewsChannel');
		return $channelTbl->find($this->news_channel_id)->current();
	}
	
	
	/**
	 * Returns newsitem's validity
	 *
	 * @return bool
	 */
	public function isValid()
	{
		$now = new DateTime();
		
		if($this->valid_start === null)
			$valid_start = true;
		else {
			
			$date_start = new DateTime($this->valid_start);
			if($date_start <= $now)
				$valid_start = true;
			else
				$valid_start = false;
		}
		
		if($this->valid_end === null)
			$valid_end = true;
		else {
			
			$date_end = new DateTime($this->valid_end);
			if($date_end >= $now)
				$valid_end = true;
			else
				$valid_end = false;
		}
		
		
		return ($valid_end && $valid_start && $this->status == 1);
		
	}
	
	
}
?>