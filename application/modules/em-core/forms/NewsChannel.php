<?php
class EmCore_Form_NewsChannel extends ZendX_JQuery_Form
{

    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAction(EMERALD_URL_BASE . "/em-core/news-channel/save");

        $idElm = new Zend_Form_Element_Hidden('id');
        $idElm->setDecorators(array('ViewHelper'));

        $pageIdElm = new Zend_Form_Element_Hidden('page_id');
        $pageIdElm->setDecorators(array('ViewHelper'));

        // $contentElm = new Zend_Form_Element_Textarea('content', array('class' => array('tinymce', 'w100')));

        $titleElm = new Zend_Form_Element_Text('title', array('label' => 'Title'));
        $titleElm->addValidator(new Zend_Validate_StringLength(0, 255));
        $titleElm->setRequired(true);
        $titleElm->setAllowEmpty(false);
        	
        $descriptionElm = new Zend_Form_Element_Text('description', array('label' => 'Description'));
        $descriptionElm->addValidator(new Zend_Validate_StringLength(0, 255));
        $descriptionElm->setRequired(false);
        $descriptionElm->setAllowEmpty(true);

        $readmoreElm = new Zend_Form_Element_Text('link_readmore', array('label' => 'Read more -link'));
        $readmoreElm->addValidator(new Zend_Validate_StringLength(0, 255));
        $readmoreElm->setRequired(true);
        $readmoreElm->setAllowEmpty(false);

        $itemsPerPageElm = new Zend_Form_Element_Select('items_per_page', array('label' => 'Items per page'));
        $itemsPerPageElm->setRequired(true);
        $itemsPerPageElm->setAllowEmpty(false);
        $itemsPerPageElm->setMultiOptions(array(1=>1, 2=>2, 3=>3, 4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,17=>17,18=>18,19=>19,20=>20));

        $monthsValidElm = new Zend_Form_Element_Select('default_months_valid', array('label' => 'Months valid'));
        $monthsValidElm->setRequired(true);
        $monthsValidElm->setAllowEmpty(false);
        $monthsValidElm->setMultiOptions(array(1=>1, 2=>2, 4=>4, 8=>8, 12=>12, 24=>24, 36=>36));

        $syndicationElm = new Zend_Form_Element_Checkbox('allow_syndication', array('label' => 'Allow syndication'));

        $submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));

        $this->addElements(array($idElm, $pageIdElm, $titleElm, $descriptionElm, $readmoreElm, $itemsPerPageElm, $monthsValidElm, $syndicationElm, $submitElm));

    }


}
