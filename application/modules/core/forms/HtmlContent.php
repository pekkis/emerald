<?php
class Core_Form_HtmlContent extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
		$this->setAttrib('id', 'htmlcontent');
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction('/core/html-content/save');

		$pageIdElm = new Zend_Form_Element_Hidden('page_id');		
		
		$blockIdElm = new Zend_Form_Element_Hidden('block_id');
		
		$contentElm = new Zend_Form_Element_Text('content', array('class' => array('tinymce', 'w100')));
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'common/save'));
		
		$this->addElements(array($pageIdElm, $blockIdElm, $contentElm, $submitElm));
		
		
	}
	
	
}
?>

