<?php
class EmCore_Model_NewsChannelItem extends Emerald_Cms_Model_AbstractItem
{




    protected $_paginator;

    private $_items;

    public function getPage()
    {
        $model = new EmCore_Model_Page();
        return $model->find($this->page_id);
    }


    public function getItems($invalids = false, $tag = null)
    {
        $model = new EmCore_Model_NewsChannel();
        return $model->getItems($this, $invalids, $tag);
    }



}