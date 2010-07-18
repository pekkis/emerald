<?php
class EmCore_Form_NewsItem extends ZendX_JQuery_Form
{

	public function init()
	{
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/em-core/news-item/save");
		
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
		
		$publishElm = new Zend_Form_Element_Text('valid_start_date', array('label' => 'Publish date'));
		$publishElm->setRequired(true);
		$publishElm->setAllowEmpty(false);
		$publishElm->addValidator(new Zend_Validate_Date('yyyy-MM-dd'));
		
		$publishTimeElm = new Zend_Form_Element_Text('valid_start_time', array('label' => 'Publish time (hh:mm:ss)'));
		$publishTimeElm->setRequired(true);
		$publishTimeElm->setAllowEmpty(false);
		$publishTimeElm->addValidator(new Emerald_Validate_Time());
		
		$expireElm = new Zend_Form_Element_Text('valid_end_date', array('label' => 'Expiration date'));
		$expireElm->setRequired(true);
		$expireElm->setAllowEmpty(false);
		$expireElm->addValidator(new Zend_Validate_Date('yyyy-MM-dd'));

		$expireTimeElm = new Zend_Form_Element_Text('valid_end_time', array('label' => 'Expiration time (hh:mm:ss)'));
		$expireTimeElm->setRequired(true);
		$expireTimeElm->setAllowEmpty(false);
		$expireTimeElm->addValidator(new Emerald_Validate_Time());

						
		$statusElm = new Zend_Form_Element_Checkbox('status', array('label' => 'Active'));
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		
		$this->addElements(array($idElm, $channelIdElm, $titleElm, $descriptionElm, $articleElm, $publishElm, $publishTimeElm, $expireElm, $expireTimeElm, $statusElm, $submitElm));

		$taggableModel = new EmCore_Model_Taggable();
		$tagForm = $taggableModel->getForm();
		$this->addSubForm($tagForm, 'tags', 5);
		
		
	}
		
	
}
