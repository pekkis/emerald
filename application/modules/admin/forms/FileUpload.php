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

                $submitElm = new Zend_Form_Element_Submit('Submit');

                $this->addElements(array($folderElm, $fileElm, $submitElm));


        }




}
