<?php
class Emerald_Db_Crud
{
	
	private $_models = array();
	
	public function addModels(array $models)
	{
		foreach($models as $model) {
			$this->_models[] = new $model();			
		}
	}
	
	
	
	public function getModels()
	{
		return $this->_models;
	}
	
	
	
}
