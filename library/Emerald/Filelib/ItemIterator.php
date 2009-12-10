<?php
/**
 * Filelib item-iterator extends ArrayIterator to implement toArray method.
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_ItemIterator extends ArrayIterator
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