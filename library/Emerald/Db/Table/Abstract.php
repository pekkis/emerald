<?php
class Emerald_Db_Table_Abstract extends Zend_Db_Table_Abstract 
{
	
	public function createRow(array $data = array(), $initializeWithDefaults = false)
    {
	
    	$row = parent::createRow($data);
		if($initializeWithDefaults) {
			$info = $this->info();
			foreach($info['metadata'] as $field => $meta) {
				if($meta['DEFAULT'])
					$row->$field = $meta['DEFAULT'];				
			}
		}
    	

		return $row;
    	
    	
    }
	
	
	
	
	
}
