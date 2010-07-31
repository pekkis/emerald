<?php
class EmCore_Model_FormItem extends Emerald_Model_AbstractItem
{


    public function getZendForm()
    {
        $form = new Zend_Form();
        $form->setMethod(Zend_Form::METHOD_POST);

        $idElm = new Zend_Form_Element_Hidden('id');
        $idElm->setDecorators(array('ViewHelper'));
        $idElm->setValue($this->id);

        $form->addElement($idElm);

        foreach($this->getFields() as $key => $field)
        {
            	
            switch($field->type) {

                case 2:
                    $elm = new Zend_Form_Element_Textarea("form_{$this->id}_field_{$field->id}", array('label' => $field->title));
                    $elm->addValidator(new Zend_Validate_StringLength(0, 1000));
                    if($field->mandatory) {
                        $elm->setRequired(true);
                        $elm->setAllowEmpty(false);
                    } else {
                        $elm->setRequired(false);
                        $elm->setAllowEmpty(true);
                    }
                    break;
                    	
                case 3:
                    $elm = new Zend_Form_Element_Select("form_{$this->id}_field_{$field->id}", array('label' => $field->title));
                    $opts = array("" => "") + explode("\n", trim($field->options));
                    $elm->setMultiOptions($opts);
                    if($field->mandatory) {
                        $elm->setRequired(true);
                        $elm->setAllowEmpty(false);
                    } else {
                        $elm->setRequired(false);
                        $elm->setAllowEmpty(true);
                    }
                    break;
                    	
                case 4:
                    $elm = new Zend_Form_Element_Multiselect("form_{$this->id}_field_{$field->id}", array('label' => $field->title));
                    $opts = array("" => "") + explode("\n", trim($field->options));
                    $elm->setMultiOptions($opts);
                    if($field->mandatory) {
                        $elm->setRequired(true);
                        $elm->setAllowEmpty(false);
                    } else {
                        $elm->setRequired(false);
                        $elm->setAllowEmpty(true);
                    }
                    break;

                case 5:
                    $elm = new Zend_Form_Element_Radio("form_{$this->id}_field_{$field->id}", array('label' => $field->title));
                    $opts = explode("\n", trim($field->options));
                    $elm->setMultiOptions($opts);
                    if($field->mandatory) {
                        $elm->setRequired(true);
                        $elm->setAllowEmpty(false);
                    } else {
                        $elm->setRequired(false);
                        $elm->setAllowEmpty(true);
                    }
                    break;
                    	
                case 6:
                    $elm = new Zend_Form_Element_Checkbox("form_{$this->id}_field_{$field->id}", array('label' => $field->title));
                    if($field->mandatory) {
                        $elm->setRequired(true);
                        $elm->setAllowEmpty(false);
                    } else {
                        $elm->setRequired(false);
                        $elm->setAllowEmpty(true);
                    }
                    break;
                    	
                default:
                    $elm = new Zend_Form_Element_Text("form_{$this->id}_field_{$field->id}", array('label' => $field->title));
                    $elm->addValidator(new Zend_Validate_StringLength(0, 255));
                    if($field->mandatory) {
                        $elm->setRequired(true);
                        $elm->setAllowEmpty(false);
                    } else {
                        $elm->setRequired(false);
                        $elm->setAllowEmpty(true);
                    }
                    	
            }
            	

            $form->addElement($elm);
            	
        }

        $submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Submit'));
        $form->addElement($submitElm);

        return $form;


    }



    public function getFields()
    {
        $model = new EmCore_Model_Form();
        return $model->getFields($this);
    }

    public function findFieldById($id)
    {
        $model = new EmCore_Model_Form();
        return $model->findFieldById($this, $id);
    }

}