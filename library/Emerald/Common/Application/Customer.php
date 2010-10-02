<?php
/**
 * Customer
 * 
 * @author pekkis
 * @package Emerald_Common_Application
 *
 */
class Emerald_Common_Application_Customer
{
    /**
     * @var string Identifier
     */
    private $_identifier;
        
    /**
     * @var string Root directory
     */
    private $_root;
    
    /**
     * @var array Array of specialized roots
     */
    private $_roots = array(
		'layouts' => 'views/scripts/layouts',
    );

    /**
     * @var Zend_Config Configuration
     */
    private $_config;


    /**
     * @var Zend_Cache_Core Option cache
     */
    private $_optionCache;


    /**
     * @var Zend_Db_Adapter_Pdo_Mysql
     */
    private $_db;

    /**
     * @var Emerald_Common_Db_OptionContainer Option container
     */
    private $_optionContainer;

    /**
     * @var array Options
     */
    private $_options = array();

    public function __construct($root)
    {
        $this->_root = $root;

        $pinfo = pathinfo($root);
        $this->_identifier = $pinfo['basename'];

    }

    
    public function getIdentifier()
    {
        return $this->_identifier;
    }
    
    /**
     * Returns the customer's root directory, or a specialized root if specified
     *
     * @param string $specified Specialized root
     * @return string Root directory
     */
    public function getRoot($specified = null)
    {
        if($specified) {
            return $this->_root . '/' . $this->_roots[$specified]; 
        }
        return $this->_root; 
    }

    /**
     * Returns config
     *
     * @return Zend_Config_Ini
     */
    public function getConfig()
    {
        if(!$this->_config) {
            $this->_config = new Zend_Config_Ini($this->getRoot() . '/configs/emerald.ini');
        }
        return $this->_config;
    }

    /**
     * Returns Db
     *
     * @return Zend_Db_Adapter_Pdo_Mysql
     */
    public function getDb()
    {
        return $this->_db;
    }



    /**
     * Sets db
     * 
     * @param unknown_type $db
     */
    public function setDb(Zend_Db_Adapter_Abstract $db)
    {
        $this->_db = $db;
    }


    /**
     * Returns option cache
     * 
     * @return Zend_Cache_Core
     */
    public function getOptionCache()
    {
        if(!$this->_optionCache) {
            $this->_optionCache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
        }
        return $this->_optionCache;
    }


    /**
     * Returns option
     * 
     * @param string $key
     * @return mixed Option value or false
     */
    public function getOption($key)
    {
        $options = $this->getOptions();
        return (isset($options[$key])) ? $options[$key] : false;
    }


    /**
     * Sets option
     * 
     * @param string $key Key
     * @param mixed $value Value
     */
    public function setOption($key, $value)
    {
        $this->_options[$key] = $value;
        $this->_getOptionContainer()->$key = $value;
        $this->getOptionCache()->remove('application_options');

    }

    /**
     * Returns all options
     * 
     * @return array
     */
    public function getOptions()
    {
        if(!$this->_options) {
            $this->_options = $this->getOptionCache()->load('application_options');
            if($this->_options === false) {
                $this->_options = $this->_getOptionContainer()->getOptions();
                $this->getOptionCache()->save($this->_options, 'application_options');
            }
        }
        return $this->_options;
    }



    /**
     * Returns option container
     * 
     * @return Emerald_Common_Db_OptionContainer
     */
    protected function _getOptionContainer()
    {
        if(!$this->_optionContainer) {
            $this->_optionContainer = new Emerald_Common_Db_OptionContainer();
            $this->_optionContainer->setTable(new EmCore_Model_DbTable_Application_Option);

        }
        return $this->_optionContainer;
    }


    /**
     * Returns all available layout classes
     * 
     * @return ArrayObject
     */
    public function getLayouts()
    {
        $iter = new DirectoryIterator($this->getRoot() . '/views/scripts/layouts');
        $layouts = new ArrayObject();
        foreach($iter as $file) {
            if($file->isFile() && preg_match("/\.php$/", $file->getFilename())) {
                $layoutName = basename($file->getFilename(), '.php');
                $layouts[$layoutName] = $this->getLayout($layoutName);
            }
        }
        $layouts->ksort();
        return $layouts;
    }



    /**
     * Returns the specified layout object
     * 
     * @param string $layout Layout name
     * @return Emerald_Layout
     */
    public function getLayout($layout)
    {
        require_once $this->getRoot() . "/views/scripts/layouts/{$layout}.php";
        $className = "Emerald_Layout_{$layout}";
        $layout = new $className();
        return $layout;
    }

    /**
     * Returns whether the customer is registered to the server
     * 
     * @return boolean
     */
    public function isRegistered()
    {
        return (bool) $this->getOption('registered');
    }

    /**
     * Returns whether the customer is installed
     * 
     * @return boolean
     * @todo Is this a bug?
     */
    public function isInstalled()
    {
        return (bool) $this->getOption('registered');
    }


}
?>