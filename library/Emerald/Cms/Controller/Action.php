<?php
/**
 * CMS specific controller action
 *
 * @package Emerald_Common_Controller
 * @author pekkis
 *
 */
class Emerald_Cms_Controller_Action extends Zend_Controller_Action
{
    /**
     * @var EmCore_Model_Page
     */
    protected static $_pageModel;

    /**
     * Returns customer
     *
     * @return Emerald_Common_Application_Customer
     */
    public function getCustomer()
    {
        return $this->getInvokeArg('bootstrap')->getResource('customer');
    }

    /**
     * Returns current user
     *
     * @return EmCore_Model_UserItem
     */
    public function getCurrentUser()
    {
        return $this->getInvokeArg('bootstrap')->getResource('emuser');
    }

    /** 
     * Assigns correct response segments
     */
    public function postDispatch()
    {
        if($rs = $this->_getParam('rs')) {
            $this->getHelper('viewRenderer')->setResponseSegment($rs);
            $this->view->emerald_rs = $this->_getParam('rs');
        } else {
            $this->view->emerald_rs = 'content';
        }
    }

    /**
     * Returns db
     * 
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDb()
    {
        return $this->getInvokeArg('bootstrap')->getResource('emdb');
    }

    /**
     * Returns ACL
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        return $this->getInvokeArg('bootstrap')->getResource('emacl');
    }

    /**
     * Returns page model
     * 
     * @return EmCore_Model_Page
     */
    protected function _getPageModel()
    {
        if(!self::$_pageModel) {
            self::$_pageModel = new EmCore_Model_Page();
        }

        return self::$_pageModel;
    }

    /**
     * Returns page from page id
     * 
     * @param int $pageId
     * @return EmCore_Model_PageItem
     */
    protected function _pageFromPageId($pageId)
    {
        return $this->_getPageModel()->find($pageId);
    }

}