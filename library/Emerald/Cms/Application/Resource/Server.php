<?php
/**
 * Initializes Emerald Server
 * 
 * @author pekkis
 * @package Emerald_Cms_Application
 *
 */
class Emerald_Common_Application_Resource_Server extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @return Emerald_Cms_Server
     */
    public function init()
    {
        $options = $this->getOptions();
        $server = new Emerald_Cms_Server($options);
        Zend_Registry::set('Emerald_Cms_Server', $server);
        return $server;
    }


}