<?php
class Admin_Form_FormFieldCreate extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction("/admin/form/field-create");
		

		$this->setAttrib('id', 'field-create');
		
		$idElm = new Zend_Form_Element_Hidden('form_id');
		$idElm->setDecorators(array('ViewHelper'));
		
		$typeElm = new Zend_Form_Element_Select('type', array('label' => 'Field type'));
		
		
			$opts = array(
			'1' => 'l:admin/form/field/type/1',
			'2' => 'l:admin/form/field/type/2',
			'3' => 'l:admin/form/field/type/3',
			'4' => 'l:admin/form/field/type/4',
			'5' => 'l:admin/form/field/type/5',
			'6' => 'l:admin/form/field/type/6',
			);
		
		$typeElm->addMultiOptions($opts);
				
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Add field'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($idElm, $typeElm, $submitElm));
		
	}
	
	
	
}
			
			
?>	
