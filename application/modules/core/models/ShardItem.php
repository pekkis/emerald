<?php
class Core_Model_ShardItem extends Emerald_Model_AbstractItem
{

	public function isInsertable()
	{
		return ($this->status & Core_Model_Shard::INSERTABLE);
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