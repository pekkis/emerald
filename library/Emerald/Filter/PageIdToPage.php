<?php
class Emerald_Filter_PageIdToPage implements Zend_Filter_Interface 
{

	public function filter($value)
	{
		if($value instanceof Core_Model_PageItem) {
			return $value;
		}

		$pageModel = new Core_Model_Page();
		return $pageModel->find($value);
		
	}
	
}


?>