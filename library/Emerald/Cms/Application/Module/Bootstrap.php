<?php
class Emerald_Cms_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{
    
    
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
            $this->setResourceLoader(new Emerald_Cms_Application_Module_Autoloader(array(
                'namespace' => $namespace,
                'basePath'  => dirname($path),
            )));
        }
        return $this->_resourceLoader;
    }
    
}