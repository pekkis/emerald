<?php
class EmAdmin_Form_CreateFolder extends Zend_Form
{

        public function init()
        {
                $this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
                $this->setAction(EMERALD_URL_BASE . '/admin/filelib/create-folder');
                $this->setAttrib('id', 'create-folder');

                $folderElm = new Zend_Form_Element_Hidden('parent_id');
                $folderElm->addValidator(new Zend_Validate_Int());
				$folderElm->setRequired(true);
				$folderElm->setAllowEmpty(false);
				
                $nameElm = new Zend_Form_Element_Text('name', array('label' => 'Folder name'));
				$nameElm->addFilter(new Zend_Filter_Alnum(false));
                $nameElm->addValidator(new Zend_Validate_StringLength(1, 50));
				$nameElm->setRequired(true);
				$nameElm->setAllowEmpty(false);
                
                $submitElm = new Zend_Form_Element_Submit('Submit');
                $submitElm->setIgnore(true);

                $this->addElements(array($folderElm, $nameElm, $submitElm));


        }




}
