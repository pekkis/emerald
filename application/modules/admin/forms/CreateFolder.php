<?php
class Admin_Form_CreateFolder extends Zend_Form
{

        public function init()
        {
                $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
                $this->setAction('/admin/filelib/create-folder');

                $folderElm = new Zend_Form_Element_Hidden('parent_id');
                $folderElm->addValidator(new Zend_Validate_Int());
				$folderElm->isRequired(true);
                
				
                $nameElm = new Zend_Form_Element_Text('name', array('label' => 'Folder name'));
				$nameElm->addFilter(new Zend_Filter_Alnum(false));
                $nameElm->addValidator(new Zend_Validate_StringLength(1, 50));
				$nameElm->isRequired(true);
                
                $submitElm = new Zend_Form_Element_Submit('Submit');
                $submitElm->setIgnore(true);

                $this->addElements(array($folderElm, $nameElm, $submitElm));


        }




}
