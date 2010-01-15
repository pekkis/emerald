<?php 
class Emerald_Layout_Front extends Emerald_Layout
{
	
	
	
	protected function _run()
	{
		$this->actionToStack('index', 'html-content', 'emerald', array('page_id' => $this->getPage()->id, 'block_id' => 3, 'rs' => 'sidebar_two'));
		$this->shard(4, 'News', array('a' => 'headlines', 'amount' => 3, 'rs' => 'sidebar_one'));	
		$this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
	}
	
	protected function _runAjax()
	{
		$this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
	}
	
	
	
}