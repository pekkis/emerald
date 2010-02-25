<?php
class Admin_Form_FileUpload extends Zend_Form
{

        public function init()
        {
                $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
                $this->setAction('/admin/filelib/submit');
				$this->setAttrib('id', 'file-upload');
                
                $folderElm = new Zend_Form_Element_Hidden('folder_id');
                $folderElm->addValidator(new Zend_Validate_Int());

                $fileElm = new Zend_Form_Element_File('file');

                $fl = Zend_Registry::get('Emerald_Filelib');

                $profileElm = new Zend_Form_Element_Select('profile', array('label' => 'File profile'));
                $profileElm->setMultiOptions($fl->getProfiles());
                $profileElm->setValue('default');
                $profileElm->setAllowEmpty(false);
                $profileElm->setRequired(true);
                                
                $submitElm = new Zend_Form_Element_Submit('Submit');

                $this->addElements(array($folderElm, $profileElm, $fileElm, $submitElm));


        }




}
