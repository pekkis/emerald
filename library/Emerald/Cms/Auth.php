<?php
/**
 * Simple Auth wrapper to ensure that no clash happens with the host application
 * 
 * @author pekkis
 * @package Emerald_Cms_Auth
 *
 */
class Emerald_Cms_Auth
{

    /**
     * Returns Zend auth with Emerald user session space.
     *
     * @return Zend_Auth
     */
    static public function getInstance()
    {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Emerald_User'));
        return $auth;
    }




}