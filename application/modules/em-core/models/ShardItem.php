<?php
class EmCore_Model_ShardItem extends Emerald_Model_AbstractItem
{

	public function isInsertable()
	{
		return ($this->status & EmCore_Model_Shard::INSERTABLE);
	}
	
	
	
	public function getDefaultAction()
	{
		return array(
			'module' => $this->module,
			'controller' => $this->controller,
			'action' => $this->action
		);
	}
	
	
}