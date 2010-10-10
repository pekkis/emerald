<?php
/**
 * Emerald CMS specific bootstrap ensures that customer is bootstrap always and first.
 *  
 * @author pekkis
 * @package Emerald_Common_Application
 *
 */
class Emerald_Common_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _bootstrap($resource = null)
    {
        if($resource === null) {
            if(!isset($this->_run['customer'])) {
                $this->bootstrap('customer');
            }
            $ret = parent::_bootstrap($resource);
            return $ret;
        }
        $ret = parent::_bootstrap($resource);
    }

    
    /**
     * Adds options
     * 
     * @param array $options
     * @return Emerald_Common_Application_Bootstrap_Bootstrap
     */
    public function addOptions(array $options)
    {
        return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
    }

    
    /**
     * Retrieve module resource loader
     *
     * @return Zend_Loader_Autoloader_Resource
     */
    public function getResourceLoader()
    {
     
        if ((null === $this->_resourceLoader)
            && (false !== ($namespace = $this->getAppNamespace()))
        ) {
            $r    = new ReflectionClass($this);
            $path = $r->getFileName();
            $this->setResourceLoader(new Emerald_Application_Module_Autoloader(array(
                'namespace' => $namespace,
                'basePath'  => dirname($path),
            )));
        }
        return $this->_resourceLoader;
    }
    
    
    
}