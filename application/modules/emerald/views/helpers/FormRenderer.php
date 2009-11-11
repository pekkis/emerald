<?php
class Emerald_View_Helper_FormRenderer
{
	private $_form;		
	
	private $_view;
	
	public function formRenderer($form)
	{
		$this->_form = $form;
		
		return $this->_render();
	
	}
	

	private function _render()
	{
		$this->_view->form = $this->_form;
		
		$output = '';
		foreach($this->_form->getFields() as $field) {
			
			$options = array();
			
			if(in_array($field->type, array(3))) {
				$options[''] = '';
			}
			
						
			if($field->options) {
								
				$optionsRaw = explode("\n", $field->options);
								
				foreach($optionsRaw as $value) {
					$options[$value] = $value;
				}
				
				
			}
			
			$this->_view->options = $options;
			
			$this->_view->field = $field;
			$output .= $this->_view->render("formcontent/field/{$field->type}.phtml");
		}
		
		return $output;
		
	}
	
	
	public function setView($view)
	{
		$this->_view = $view;
	}
	
	
}

?>