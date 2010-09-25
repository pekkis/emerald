<?php
/**
 * Extended db resource sets the customer's db and correct fetch mode
 * 
 * @author pekkis
 * @package Emerald_Application
 *
 */
class Emerald_Application_Resource_Emdb extends Zend_Application_Resource_Db
{

    protected $_customer;

    /**
     * @return Zend_Db_Adapter_Abstract
     */
    public function init()
    {
        $db = parent::init();
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        $this->getCustomer()->setDb($db);
        Zend_Registry::set('Emerald_Db', $db);
        return $db;
    }



    /**
     * @return Emerald_Application_Customer
     */
    public function getCustomer()
    {
        return $this->getBootstrap()->getResource('customer');
    }


}