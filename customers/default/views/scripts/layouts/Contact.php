<?php 
class Emerald_Layout_Contact extends Emerald_Layout
{
	
	
	
	protected function _run()
	{
		// $this->actionToStack('index', 'html-content', 'emerald', array('page' => $this->getPage(), 'block_id' => 2, 'rs' => 'sidebar_one'));
		
		$this->actionToStack('index', 'html-content', 'emerald', array('page_id' => $this->getPage()->id, 'block_id' => 3, 'rs' => 'sidebar_one'));
				
		$this->actionToStack('index', 'menu', 'core', array('page_id' => $this->getPage()->id, 'extended' => 1, 'rs' => 'navi'));
		
		$this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
		
		$this->actionToStack('index', 'html-content', 'emerald', array('page_id' => $this->getPage()->id, 'block_id' => 4, 'rs' => ''));
		
	}
	
	protected function _runAjax()
	{
		$this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
	}
	
	
	
}