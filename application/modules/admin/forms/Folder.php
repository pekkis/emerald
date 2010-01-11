<?php
class Admin_Form_Folder extends ZendX_JQuery_Form
{

	public function init()
	{
				
		
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction("/admin/folder/save");

		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
						
		$nameElm = new Zend_Form_Element_Text('name', array('label' => 'Name', 'class' => 'w66'));
		$nameElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$nameElm->setRequired(true);
		$nameElm->setAllowEmpty(false);
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($idElm, $nameElm, $submitElm));
		
		$permissionForm = new Admin_Form_FolderPermissions();
		$permissionForm->setAttrib('id', 'folder-permissions');
		$this->addSubForm($permissionForm, 'folder-permissions', 2);
		
	}
	
	
}