<?php
class EmCore_Model_ShardItem extends Emerald_Cms_Model_AbstractItem
{

    private $_router;

    /**
     * Returns router
     *
     * @return Zend_Controller_Router_Rewrite
     */
    public function getRouter()
    {
        if(!$this->_router) {
            $this->_router = Zend_Controller_Front::getInstance()->getRouter();
        }

        return $this->_router;
    }


    public function isInsertable()
    {
        return ($this->status & EmCore_Model_Shard::INSERTABLE);
    }



    public function getDefaultAction()
    {
        return array(
			'module' => $this->module,
			'controller' => $this->controller,
			'action' => $this->action
        );
    }


    public function getRoutes($page)
    {
        return array();
    }



    public function getNavigation($page)
    {
        return array();
    }





}