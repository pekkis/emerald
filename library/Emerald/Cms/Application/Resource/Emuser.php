<?php
/**
 * CMS specific user initialization
 * 
 * @author pekkis
 * @package Emerald_Cms_Application
 *
 */
class Emerald_Cms_Application_Resource_Emuser extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        $this->getBootstrap()->bootstrap('modules');

        $options = $this->getOptions();
        
        EmCore_Model_User::setHashAlgorithm($options['hash']['algorithm']);
        EmCore_Model_User::setHashSalt($options['hash']['salt']);

        $auth = Emerald_Cms_Auth::getInstance();
        if($auth->hasIdentity()) {
            $user = $auth->getIdentity();
        } else {
            $userModel = new EmCore_Model_User();
            $user = $userModel->findAnonymous();
            if(!$user) {
                throw new Emerald_Common_Exception('Something wrong with ur user');
            }
        }

        Zend_Registry::set('Emerald_User', $user);

        return $user;

    }






}