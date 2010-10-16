<?php
use Doctrine\ORM\EntityManager,  Doctrine\ORM\Configuration;

class Emerald_Cms_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    
    public function init()
    {
        $options = $this->getOptions();
        
        
        $front = Zend_Controller_Front::getInstance();

        
        
        $modules = $this->getBootstrap()->bootstrap('modules')->getResource('modules');
        
        
        $dirs = array();
        foreach($modules as $key => $module) {
            $dirs[] = APPLICATION_PATH . '/modules/' . $key . '/entities';
        }        
        
        // kludger for filelib
        
        $dirs[] = realpath(APPLICATION_PATH . '/../library/Emerald/Filelib/Backend/Doctrine2/Entity');
        
        if (APPLICATION_ENV == "development") {
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        } else {
            $cache = new \Doctrine\Common\Cache\ApcCache;
        }

        $config = new Configuration;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver($dirs);
                
        $config->setMetadataDriverImpl($driverImpl);
        
        $config->setQueryCacheImpl($cache);
        
        $config->setProxyDir(realpath(APPLICATION_PATH . '/../data/proxies'));
        $config->setProxyNamespace('Emerald\Cms\Proxies');

        if (APPLICATION_ENV == "development") {
            $config->setAutoGenerateProxyClasses(true);
        } else {
            $config->setAutoGenerateProxyClasses(false);
        }


        $em = EntityManager::create($options['connectionParams'], $config);
        
        Zend_Registry::set('Emerald_EntityManager', $em);
        return $em;
        
        
    }
    
    
    
    
    
}

