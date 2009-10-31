<?php
class Emerald_Db_Table_Row_Form extends Zend_Db_Table_Row_Abstract 
{

	
	
	public function getFields()
	{
		
		$fieldTbl = Emerald_Model::get('Form_Field');
		
		return $fieldTbl->fetchAll(
			array('form_id = ?' => $this->id), array('order_id ASC')
		);
				
		
	}
	
	
}
?>