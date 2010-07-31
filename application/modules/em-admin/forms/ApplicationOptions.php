<?php
class EmAdmin_Form_ApplicationOptions extends ZendX_JQuery_Form
{


    public function init()
    {

        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAction(EMERALD_URL_BASE . "/em-admin/options/save-application");



        $dlElm = new Zend_Form_Element_Select('default_locale', array('label' => 'Default locale'));

        // $customer = Zend_Registry::get('Emerald_Customer');

        $localeModel = new EmCore_Model_Locale();
        $locales = $localeModel->findAll();

        foreach($locales as $locale) {
            $dlElm->addMultiOption($locale->locale, $locale->locale);
        }

        $dlElm->addValidator(new Zend_Validate_Regex("/^([a-z]{2,3}(_[A-Z]{2})?)$/"));
        $dlElm->setRequired(true);


        $gaElm = new Zend_Form_Element_Text('google_analytics_id', array('label' => 'Google analytics id'));
        $gaElm->addValidator(new Zend_Validate_StringLength(0, 20));
        $gaElm->setRequired(false);
        $gaElm->setAllowEmpty(true);
        	

        $submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
        $submitElm->setIgnore(true);

        $this->addElements(array($dlElm, $gaElm, $submitElm));


    }




}






?>

