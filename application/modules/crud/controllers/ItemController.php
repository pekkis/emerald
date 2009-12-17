<?php
class Crud_ItemController extends Zend_Controller_Action
{
	
	
	
	public function getForm(Zend_Db_Table_Abstract $model)
	{
		$form = new Zend_Form();
		$form->setMethod(Zend_Form::METHOD_POST);

		$form->setAction("/crud/item/save/model/" . get_class($model));
		
		$pk = $model->info('primary');
		
		
		$cols = $model->info('cols');
		
		$metadata = $model->info('metadata');

		// Zend_Debug::dump($metadata);
		
		foreach($cols as $col) {

			$meta = $metadata[$col];
			
			switch($meta['DATA_TYPE']) {

				case "text":
					$elm = new Zend_Form_Element_Textarea($col, array('label' => $col));
					break;
				
				default:
					$elm = new Zend_Form_Element_Text($col, array('label' => $col));
					break;
					
			}
			

			switch($meta['DATA_TYPE']) {

				case "bigint":
					$min = -9223372036854775808;
					$max = 9223372036854775807;
					if($meta['UNSIGNED']) {
						$max = $max + (-$min);
						$min = 0;
					}
					
					$elm->addValidator(new Zend_Validate_Between($min, $max));
					
					break;
					
				case "int":
					$min = -2147483648;
					$max = 2147483647;
					if($meta['UNSIGNED']) {
						$max = $max + (-$min);
						$min = 0;
					}
					$elm->addValidator(new Zend_Validate_Between($min, $max));
					break;

				case "smallint":
					$min = -32768;
					$max = 32767;
					if($meta['UNSIGNED']) {
						$max = $max + (-$min);
						$min = 0;
					}
					$elm->addValidator(new Zend_Validate_Between($min, $max));
					break;

				case "tinyint":
					$min = -128;
					$max = 127;
					if($meta['UNSIGNED']) {
						$max = $max + (-$min);
						$min = 0;
					}
					$elm->addValidator(new Zend_Validate_Between($min, $max));
					break;
					
				case "timestamp":
					$elm->addValidator(new Zend_Validate_Regex("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/"));
					break;
					
			
			}
			
			
			if($meta['NULLABLE']) {
				$elm->setRequired(false);
				$elm->setAllowEmpty(true);
			} else {
				$elm->setRequired(true);
				$elm->setAllowEmpty(false);
			}

			
			
			
			
			
			$form->addElement($elm);
			
		}
		
		$submit = new Zend_Form_Element_Submit('submit', array('label' => 'save'));
		$submit->setIgnore(true);
		
		$form->addElement($submit);
		
		
		return $form;
		
		
		
		
	}
	

	public function editAction()
	{
		$model = $this->_getParam('model'); 
		$model = new $model(); 
		
		$this->view->model = $model;
		
		$id = $this->_getParam('id');
		$this->view->pk = $id;
		
		$id = explode(";", $id);
		
		
		$form = $this->getForm($model);
		
		$item = call_user_func_array(array($model, 'find'), $id);
		
		
		
		// $item = $model->find($id)->current();
		
		$form->setDefaults($item->current()->toArray());
		
		$this->view->form = $form;
		
		
		
	}
	
	
	
	public function saveAction()
	{
		$model = $this->_getParam('model'); 
		$model = new $model();
			
		
		$form = $this->getForm($model);
		
		if($form->isValid($_POST)) {
			
			$pk = $model->info('primary');
			
			$pkd = array();
			foreach($pk as $p) {
				if($form->$p->getValue()) {
					$pkd[] = $form->$p->getValue();
				}				
			}			
			
			if(sizeof($pkd) == sizeof($pk)) {
				$item = call_user_func_array(array($model, 'find'), $pkd)->current();
				if(!$item) {
					$item = $model->createRow();
				}
				
			} else {
				$item = $model->createRow();
			}
			
			$item->setFromArray($form->getValues());
				
			$item->save();			
			
			die('Great success!');
			
		} else {
			$this->view->model = $model;
			$this->view->form = $form;
			$this->getHelper('viewRenderer')->setScriptAction('edit');
		}
		
		
	}
	
	
}
