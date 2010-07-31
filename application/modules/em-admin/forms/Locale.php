<?php
class EmAdmin_Form_Locale extends ZendX_JQuery_Form
{

    public function init()
    {
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAction(EMERALD_URL_BASE . "/em-admin/locale/save");

        $localeElm = new Zend_Form_Element_Hidden('locale');
        $localeElm->setDecorators(array('ViewHelper'));

        $submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
        $submitElm->setIgnore(true);

        $this->addElements(array($localeElm, $submitElm));

        $permissionForm = new EmAdmin_Form_LocalePermissions();
        $permissionForm->setAttrib('id', 'locale-permissions');

        $this->addSubForm($permissionForm, 'locale-permissions', 1);

    }




}