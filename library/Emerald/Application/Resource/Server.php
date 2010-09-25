<?php
/**
 * Initializes Emerald Server
 * 
 * @author pekkis
 * @package Emerald_Application
 *
 */
class Emerald_Application_Resource_Server extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @return Emerald_Server
     */
    public function init()
    {
        $options = $this->getOptions();
        $server = new Emerald_Server($options);
        Zend_Registry::set('Emerald_Server', $server);
        return $server;
    }


}