<?php
class Emerald_Application_Resource_Server extends Zend_Application_Resource_ResourceAbstract
{

    private $_db;


    public function init()
    {
        $options = $this->getOptions();
        $server = new Emerald_Server($options);
        Zend_Registry::set('Emerald_Server', $server);
        return $server;
    }






}