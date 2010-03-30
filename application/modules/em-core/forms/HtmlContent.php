<?php
class EmCore_Form_HtmlContent extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
		$this->setAttrib('id', 'htmlcontent');
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . '/em-core/html-content/save');


		
		$pageIdElm = new Zend_Form_Element_Hidden('page_id');		
		$pageIdElm->setDecorators(array('ViewHelper'));
		
		$blockIdElm = new Zend_Form_Element_Hidden('block_id');
		$blockIdElm->setDecorators(array('ViewHelper'));
		
		$contentElm = new Zend_Form_Element_Textarea('content', array('label' => 'Content', 'class' => array('tinymce', 'w100')));
		// $contentElm->setFilters(array(new Emerald_Filter_HtmlSpecialChars()));
		
		$siblingsElm = new Zend_Form_Element_Select('siblings', array('label' => 'This block in different locales'));
		$siblingsElm->setRegisterInArrayValidator(false);
		$siblingsElm->setRequired(false);
		$siblingsElm->setAllowEmpty(true);
		$siblingsElm->setIgnore(true);
				
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		
		$this->addElements(array($pageIdElm, $blockIdElm, $contentElm, $siblingsElm, $submitElm));
		
		
	}
	
	
}
?>

