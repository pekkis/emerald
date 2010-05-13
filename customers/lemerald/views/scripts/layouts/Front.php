<?php 
class Emerald_Layout_Front extends Emerald_Layout
{
	
	
	
	protected function _run()
	{
		$this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
		$this->shard(4, 'News', array('rs' => 'sidebar', 'a' => 'headlines'));
		$this->shard($this->getPage(), 'Html', array('rs' => 'sidebar', 'a' => 'index', 'block_id' => 2));
				
	}
	
	protected function _runAjax()
	{
		$this->_run();
	}
	
	
	
}