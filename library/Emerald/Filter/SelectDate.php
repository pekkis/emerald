<?php
class Emerald_Filter_SelectDate implements Zend_Filter_Interface 
{
	
	
	public function filter($value)
	{
		if(!isset($value['year']) || !isset($value['month']) || !isset($value['day']) || !isset($value['minute']) || !isset($value['hour'])) 
			return null;

		return $value['year'] . '-' . $value['month'] . '-' . $value['day'] . ' ' . $value['hour'] . ':' . $value['minute'];
			
	}
	
	
	
}
?>