<?php
/**
 * Common controller plugin
 * 
 * @author pekkis
 * @package Emerald_Common_Controller
 *
 */
class Emerald_Cms_Controller_Plugin_Common extends Zend_Controller_Plugin_Abstract
{


    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $customer = Zend_Registry::get('Emerald_Customer');
        $config = Zend_Registry::get('Emerald_Config');
        
        // If maintenance mode is active, forward to maintenance error
        if(isset($config['emerald']['maintenance']['enabled']) && $config['emerald']['maintenance']['enabled'] == true) {
            $request->setModuleName('em-core');
            $request->setControllerName('error');
            $request->setActionName('maintenance');
            return;
        }

        // Register customer after installation
        if($customer->isInstalled() && !$customer->isRegistered()) {
            $server = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('server');
            $server->registerCustomer($customer);
        }

        // Initialize navigation stuff        
        $view = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        $naviHelper = $view->getHelper('Navigation');

        $user = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('emuser');
        $acl = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('emacl');

        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole($user);
        
        // Define which navi to register to navi container (admin or site)
        if($request->getModuleName() == 'em-admin') {
            	
            $model = new EmAdmin_Model_Navigation();
            $navigation = $model->getNavigation();
            	
            // $aclPlugin = new Emerald_Common_Controller_Plugin_Acl($acl, $user);
            // $aclPlugin->setErrorPage('index', 'login', 'em-core');

            // Zend_Controller_Front::getInstance()->registerPlugin($aclPlugin);
            	
            // $this->view->translate()->setTranslator(Zend_Registry::get('Zend_Translate'));
            // $this->view->translate()->setLocale(Zend_Registry::get('Zend_Locale') ? Zend_Registry::get('Zend_Locale') : 'en');

        } else {
            $model = new EmCore_Model_Navigation();
            $navigation = $model->getNavigation();
            	
        }

        $naviHelper->setContainer($navigation);




    }




}