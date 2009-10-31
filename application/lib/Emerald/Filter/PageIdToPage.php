<?php
class Emerald_Filter_PageIdToPage implements Zend_Filter_Interface 
{
	
	/**
	 * Does the actual langlib filtering
	 *
	 * @param string $value Original string
	 * @return string Filtered string
	 */
	public function filter($value)
	{
		if($value instanceof Emerald_Page) {
			return $value;
		}
		
		
		return Emerald_Page::find($value);
		
	}
	
}


?>