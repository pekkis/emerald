<?php
/**
 * Extends Zend View so customer paths may always override global view scripts
 * 
 * @author pekkis
 * @package Emerald_Common_View
 * @todo Could this somehow be done lighter?
 *
 */
class Emerald_Common_View extends Zend_View
{

    /**
     * @var string Customer script path
     */
    protected $_customerPath;
    
    /**
     * Returns customer script path
     * 
     * @return string
     */
    protected function _getCustomerScriptPath()
    {
        if(!$this->_customerPath) {
            $customer = Zend_Registry::get('Emerald_Customer');
            $this->_customerPath = $customer->getRoot() . '/views/scripts/';
        }
        return $this->_customerPath;
    }


    /**
     * Finds a view script from the available directories.
     *
     * @param $name string The base name of the script.
     * @return void
     */
    protected function _script($name)
    {
        // This is Emerald's kludge
        $paths = $this->getScriptPaths();
        array_unshift($paths, $this->_getCustomerScriptPath());
        // Emerald's kludge ends.
        
        if ($this->isLfiProtectionOn() && preg_match('#\.\.[\\\/]#', $name)) {
            require_once 'Zend/View/Exception.php';
            $e = new Zend_View_Exception('Requested scripts may not include parent directory traversal ("../", "..\\" notation)');
            $e->setView($this);
            throw $e;
        }

        if (0 == count($paths)) {
            require_once 'Zend/View/Exception.php';
            $e = new Zend_View_Exception('no view script directory set; unable to determine location for view script');
            $e->setView($this);
            throw $e;
        }

        foreach ($paths as $dir) {
            if (is_readable($dir . $name)) {
                return $dir . $name;
            }
        }

        require_once 'Zend/View/Exception.php';
        $message = "script '$name' not found in path ("
        . implode(PATH_SEPARATOR, $paths)
        . ")";
        $e = new Zend_View_Exception($message);
        $e->setView($this);
        throw $e;
    }




}
