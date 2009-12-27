<?php
class Core_Form_HtmlContent extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
		$this->setAttrib('id', 'htmlcontent');
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction('/core/html-content/save');

		$pageIdElm = new Zend_Form_Element_Hidden('page_id');		
		$pageIdElm->setDecorators(array('ViewHelper'));
		
		$blockIdElm = new Zend_Form_Element_Hidden('block_id');
		$blockIdElm->setDecorators(array('ViewHelper'));
		
		$contentElm = new Zend_Form_Element_Textarea('content', array('class' => array('tinymce', 'w100')));
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		
		$this->addElements(array($pageIdElm, $blockIdElm, $contentElm, $submitElm));
		
		
	}
	
	
}
?>

