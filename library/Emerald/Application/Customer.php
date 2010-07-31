<?php
class Emerald_Application_Customer
{
    /**
     * Array of customer roots.
     *
     * @var array
     */
    private $_roots = array(
		'layouts' => 'views/scripts/layouts',
    );

    private $_root;

    /**
     * Config
     *
     * @var Zend_Config_Xml
     */
    private $_config;


    private $_optionCache;


    /**
     * Db
     *
     * @var Zend_Db_Adapter_Pdo_Mysql
     */
    private $_db;

    private $_optionContainer;

    private $_options;

    private $_identifier;

    public function __construct($root)
    {
        $this->_root = $root;

        $pinfo = pathinfo($root);
        $this->_identifier = $pinfo['basename'];

    }


    /**
     * Returns customer roots
     *
     * @param string $specified Specified root
     * @return string root directory
     */
    public function getRoot($specified = null)
    {
        return (!$specified) ? $this->_root : $this->_root . '/' . $this->_roots[$specified];
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



    public function setDb($db)
    {

        $this->_db = $db;
    }


    public function getOptionCache()
    {
        if(!$this->_optionCache) {
            $this->_optionCache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
        }
        return $this->_optionCache;
    }


    public function getOption($key)
    {
        $options = $this->getOptions();
        return (isset($options[$key])) ? $options[$key] : false;
    }


    public function setOption($key, $value)
    {
        $this->_options[$key] = $value;
        $this->_getOptionContainer()->$key = $value;
        $this->getOptionCache()->remove('application_options');

    }

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



    protected function _getOptionContainer()
    {
        if(!$this->_optionContainer) {
            $this->_optionContainer = new Emerald_Db_OptionContainer();
            $this->_optionContainer->setTable(new EmCore_Model_DbTable_Application_Option);

        }
        return $this->_optionContainer;
    }


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



    public function getLayout($layout)
    {
        require_once $this->getRoot() . "/views/scripts/layouts/{$layout}.php";
        $className = "Emerald_Layout_{$layout}";
        $layout = new $className();
        return $layout;
    }



    public function getIdentifier()
    {
        return $this->_identifier;
    }


    public function isRegistered()
    {
        return (bool) $this->getOption('registered');
    }



    public function isInstalled()
    {
        return (bool) $this->getOption('registered');
    }


}
?>