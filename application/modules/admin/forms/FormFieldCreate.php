<?php
class Admin_Form_FormFieldCreate extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(URL_BASE . "/admin/form/field-create");
		

		$this->setAttrib('id', 'field-create');
		
		$idElm = new Zend_Form_Element_Hidden('form_id');
		$idElm->setDecorators(array('ViewHelper'));
		
		$typeElm = new Zend_Form_Element_Select('type', array('label' => 'Field type'));
		
		
			$opts = array(
			'1' => 'Text',
			'2' => 'Textarea',
			'3' => 'Select',
			'4' => 'Multiselect',
			'5' => 'Radio',
			'6' => 'Checkbox',
			);
				
		$typeElm->addMultiOptions($opts);
				
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Add field'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($idElm, $typeElm, $submitElm));
		
	}
	
	
	
}
			
			
?>	
