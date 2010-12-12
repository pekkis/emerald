<?php
class EmAdmin_Form_FileUpload extends Zend_Form
{

    public function init()
    {
        $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
        $this->setAction(EMERALD_URL_BASE . '/em-admin/filelib/submit');
        $this->setAttrib('id', 'file-upload');

        $folderElm = new Zend_Form_Element_Hidden('folder_id');
        $folderElm->addValidator(new Zend_Validate_Int());

        $fileElm = new Zend_Form_Element_File('file');

        $fl = Zend_Registry::get('Emerald_Filelib');

        $profileElm = new Zend_Form_Element_Select('profile', array('label' => 'File profile'));

        foreach($fl->file()->getProfiles() as $profile) {
            if($profile->getSelectable()) {
                $profileElm->addMultiOption($profile->getIdentifier(), $profile->getDescription());
            }
        }


        $profileElm->setValue('default');
        $profileElm->setAllowEmpty(false);
        $profileElm->setRequired(true);

        $submitElm = new Zend_Form_Element_Submit('Submit');

        $this->addElements(array($folderElm, $profileElm, $fileElm, $submitElm));


    }




}
