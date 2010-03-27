<?php
class Core_Form_NewsItem extends ZendX_JQuery_Form
{

	public function init()
	{
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/core/news-item/save");
		
		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
				
		$channelIdElm = new Zend_Form_Element_Hidden('news_channel_id');		
		$channelIdElm->setDecorators(array('ViewHelper'));

		$titleElm = new Zend_Form_Element_Text('title', array('label' => 'Title', 'class' => 'w100'));
		$titleElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$titleElm->setRequired(true);
		$titleElm->setAllowEmpty(false);

		$descriptionElm = new Zend_Form_Element_Textarea('description', array('label' => 'Description', 'rows' => 3, 'class' => 'w100'));
		$descriptionElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$descriptionElm->setRequired(false);
		$descriptionElm->setAllowEmpty(true);
		
		$articleElm = new Zend_Form_Element_Textarea('article', array('label' => 'Article', 'class' => array('tinymce', 'w100')));
		$articleElm->setRequired(true);
		$articleElm->setAllowEmpty(false);
		
		$publishElm = new ZendX_JQuery_Form_Element_DatePicker('valid_start', array('label' => 'Publish', 'jQueryParams' => array('dateFormat' => 'yy-mm-dd')));
		$publishElm->setRequired(true);
		$publishElm->setAllowEmpty(false);
		$publishElm->addValidator(new Emerald_Validate_Datetime());
					
		$expireElm = new ZendX_JQuery_Form_Element_DatePicker('valid_end', array('label' => 'Expire', 'jQueryParams' => array('dateFormat' => 'yy-mm-dd')));
		$expireElm->setRequired(true);
		$expireElm->setAllowEmpty(false);
		$expireElm->addValidator(new Emerald_Validate_Datetime());
		
		$statusElm = new Zend_Form_Element_Checkbox('status', array('label' => 'Active'));
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		
		$this->addElements(array($idElm, $channelIdElm, $titleElm, $descriptionElm, $articleElm, $publishElm, $expireElm, $statusElm, $submitElm));
				
	}
		
	
}
