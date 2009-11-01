<?php
class Emerald_Db_Table_Row_CalendarEvent extends Zend_Db_Table_Row_Abstract 
{
	
	public function isValid()
	{
		$now = new DateTime();
		
		if(	$this->start_date // && $this->status == 1
			&& $this->end_date
			&& (bool)($date_start = date_create($this->start_date)))
		{
			$date_end = new DateTime($this->end_date);
			if($date_end && ($date_end >= $date_start))
				return true;
		}
		return false;
	}
	
	
}
?>