<?php 
class Emerald_Layout_Error extends Emerald_Layout
{
	
	protected function _init()
	{
		$this->setLayoutFile('error');
	}
	
	
	protected function _run()
	{
		// $this->actionToStack('index', 'html-content', 'emerald', array('page' => $this->getPage(), 'block_id' => 2, 'rs' => 'sidebar_one'));
		
		// $this->actionToStack('index', 'html-content', 'emerald', array('page' => $this->getPage(), 'block_id' => 3, 'rs' => 'sidebar_two'));
				
		$this->actionToStack('index', 'menu', 'em-core', array('page' => $this->getPage(), 'extended' => 1, 'rs' => 'navi'));
		
		// $this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
		
		
		
	}
	
	protected function _runAjax()
	{
		
		// $this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
	}
	
	
	
}
