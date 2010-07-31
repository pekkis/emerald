<?php
class EmCore_Form_Tags extends Zend_Form_SubForm
{
    private $_model;


    public function init()
    {
        $this->setLegend('Tags');

        $elm = new Zend_Form_Element_Text('tags', array('label' => 'Tags', 'class' => 'w100'));
        $elm->addFilter(new Zend_Filter_Null());
        $elm->setRequired(false);
        $elm->setAllowEmpty(true);

        $this->addElement($elm);

    }



    public function setModel(EmCore_Model_Taggable $model)
    {
        $this->_model = $model;
    }


    /**
     * Returns model
     *
     * @return EmCore_Model_Taggable
     */
    public function getModel()
    {
        return $this->_model;
    }


    public function setTaggable(EmCore_Model_TaggableItem $taggable)
    {
        if($taggable->count()) {
            $this->tags->setValue(implode(', ', $taggable->getIterator()->getArrayCopy()));
        }
    }




}
