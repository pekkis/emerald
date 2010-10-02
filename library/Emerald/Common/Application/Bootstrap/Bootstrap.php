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

}