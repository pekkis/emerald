<?php 
class Emerald_Layout_Default extends Emerald_Layout
{
	
	
	
	protected function _run()
	{
		$this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
		$this->shard($this->getPage(), 'Html', array('block_id' => 2, 'a' => 'index'));
		
		
	}
	
	protected function _runAjax()
	{
		$this->_run();
	}
	
	
	
}