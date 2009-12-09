<?php 
class Emerald_Layout_Default extends Emerald_Layout
{
	
	
	public function init()
	{
		$this->actionToStack('index', 'htmlcontent', 'emerald', array('page' => $this->getPage(), 'block_id' => 2, 'rs' => 'sidebar_one'));
		
		$this->actionToStack('index', 'htmlcontent', 'emerald', array('page' => $this->getPage(), 'block_id' => 3, 'rs' => 'sidebar_two'));
				
		$this->actionToStack('index', 'menu', 'emerald', array('page' => $this->getPage(), 'extended' => 1, 'rs' => 'navi'));
		
		$this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
		
		
	}
	
	
}