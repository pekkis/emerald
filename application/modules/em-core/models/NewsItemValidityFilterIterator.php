<?php 
class EmCore_Model_NewsItemValidityFilterIterator extends FilterIterator
{
	public function accept()
	{
		return $this->getInnerIterator()->current()->isValid();
	}
}
