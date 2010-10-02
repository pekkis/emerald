<?php
/**
 * Better view resource 
 * 
 * @author pekkis
 * @package Emerald_Common_Application
 *
 */
class Emerald_Common_Application_Resource_View extends Zend_Application_Resource_View
{

    /**
     * Retrieve view object
     *
     * @return Zend_View
     */
    public function getView()
    {
        if (null === $this->_view) {
            $options = $this->getOptions();

            // Custom view class
            if(isset($options['class'])) {
                $viewClass = $options['class'];
            } else {
                $viewClass = 'Zend_View';
            }

            $this->_view = new $viewClass($options);

            // output caching
            if(isset($options['cache'])) {
                $cache = $this->getBootstrap()->bootstrap('cache')->getResource('cache')->getCache($options['cache']);
                $this->_view->cache = $cache;
            }

            if(isset($options['doctype'])) {
                $this->_view->doctype()->setDoctype(strtoupper($options['doctype']));
            }
        }
        return $this->_view;
    }
}
