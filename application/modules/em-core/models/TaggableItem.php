<?php
class EmCore_Model_TaggableItem extends Emerald_Model_AbstractItem implements Countable, IteratorAggregate
{
	
	public function count()
	{
		return count($this->tags);
	}

	
	public function getIterator()
	{
		return new ArrayIterator($this->tags);
	}
	
	
	public function tagsToArray()
	{
		return $this->tags;
	}
	
	
	public function setFromString($str, $separator = ',')
	{
		$split = explode($separator, $str);
		array_walk($split, function(&$value, $key) { $value = trim($value); });
		$this->tags = array_unique($split);
	}
	

}