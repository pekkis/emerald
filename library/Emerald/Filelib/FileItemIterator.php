<?php
/**
 * Fileitem iterator extends ArrayIterator to implement toArray method.
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_FileItemIterator extends ArrayIterator
{

	/**
	 * Returns the collection as array.
	 * 
	 * @return array
	 */
	public function toArray()
	{
		$arr = array();
		foreach($this as $item) {
			$arr[] = $item->toArray();
		}

		return $arr;
	}

}
?>