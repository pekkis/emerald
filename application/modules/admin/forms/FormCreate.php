<?php
class Admin_Form_FormCreate extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(URL_BASE . "/admin/form/create-post");
		
		$nameElm = new Zend_Form_Element_Text('name', array('label' => 'Name', 'class' => 'w66'));
		$nameElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$nameElm->setRequired(true);
		$nameElm->setAllowEmpty(false);

		$descriptionElm = new Zend_Form_Element_Textarea('description', array('label' => 'Description', 'class' => 'w66', 'rows' => 3));
		$descriptionElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$descriptionElm->setRequired(false);
		$descriptionElm->setAllowEmpty(true);
				
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($nameElm, $descriptionElm, $submitElm));
		
	}
	
	
	
}
