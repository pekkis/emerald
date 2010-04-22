<?php
class EmCore_Form_CustomContent extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
		$this->setAttrib('id', 'customcontent');
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . '/em-core/custom-content/save');

		$pageIdElm = new Zend_Form_Element_Hidden('page_id');		
		$pageIdElm->setDecorators(array('ViewHelper'));
		
		$blockIdElm = new Zend_Form_Element_Hidden('block_id');
		$blockIdElm->setDecorators(array('ViewHelper'));

		$moduleElm = new Zend_Form_Element_Text('module', array('label' => 'Module'));
		$moduleElm->setRequired(false);
		$moduleElm->setAllowEmpty(true);

		$controllerElm = new Zend_Form_Element_Text('controller', array('label' => 'Controller'));
		$controllerElm->setRequired(false);
		$controllerElm->setAllowEmpty(true);
		
		$actionElm = new Zend_Form_Element_Text('action', array('label' => 'Action'));
		$actionElm->setRequired(false);
		$actionElm->setAllowEmpty(true);
		
		$paramsElm = new Zend_Form_Element_Text('params', array('label' => 'Params'));
		$paramsElm->setRequired(false);
		$paramsElm->setAllowEmpty(true);
		
		$siblingsElm = new Zend_Form_Element_Select('siblings', array('label' => 'This block in different locales'));
		$siblingsElm->setRegisterInArrayValidator(false);
		$siblingsElm->setRequired(false);
		$siblingsElm->setAllowEmpty(false);
		$siblingsElm->setIgnore(true);
				
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		
		$this->addElements(array($pageIdElm, $blockIdElm, $moduleElm, $controllerElm, $actionElm, $paramsElm, $siblingsElm, $submitElm));
		
		
	}
	
	
}
?>

