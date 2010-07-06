<?php
/**
 * Emerald filelib
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib
{
	/**
	 * @var Emerald_Filelib_Backend_Interface Backend
	 */
	private $_backend;
		
	/**
	 * @var Emerald_Filelib_Acl_Interface Acl handler
	 */
	private $_acl;
	
	/**
	 * @var string Physical root
	 */
	private $_root;
	
	/**
	 * @var string Physical public root
	 */
	private $_publicRoot;
	
	/**
	 * @var string Public root prefix from web root.
	 */
	private $_publicDirectoryPrefix = '';
	
	/**
	 * @var string Path to magic file (for PHP 5.2)
	 */
	private $_magic;
	
	/**
	 * @var array Array of installed plugins
	 */
	private $_plugins = array();

	/**
	 * @var string Relative path from public to private root
	 */
	private $_relativePathToRoot;
		
	/**
	 * @var integer Files per directory
	 */
	private $_filesPerDirectory = 500;
	
	/**
	 * @var integer Levels in directory structure
	 */
	private $_directoryLevels = 1;
	
		
	/**
	 * @var integer Octal representation for directory permissions
	 */
	private $_directoryPermission = 0700;

	/**
	 * @var integer Octal representation for file permissions
	 */
	private $_filePermission = 0600;

	
	/**
	 * @var string Fileitem class
	 */
	private $_fileItemClass = 'Emerald_Filelib_FileItem';

	
	/**
	 * @var string Folderitem class
	 */
	private $_folderItemClass = 'Emerald_Filelib_FolderItem';
	
	
	
	private $_fileOperator;
	
	private $_folderOperator;
	
	private $_cache;
	
		
	/**
	 * @var array Profiles
	 */
	private $_profiles = array();
	
	public function __construct($options = array())
	{
		Emerald_Options::setConstructorOptions($this, $options);
	}
	
	
	public function setCache(Zend_Cache_Core $cache)
	{
		$this->_cache = $cache;
	}
	
	
	public function getCache()
	{
		if(!isset($this->_cache)) {
			$frontendOptions = array(
			'lifetime' => 7200, // cache lifetime of 2 hours
			'automatic_serialization' => true
			);
			$backendOptions = array(
			);
			$this->_cache = Zend_Cache::factory('Core', 'Black_Hole', $frontendOptions, $backendOptions);
		}
		return $this->_cache;
	}
	
	
	
	
	public function addProfile(Emerald_Filelib_FileProfile $profile)
	{
		$profile->setFilelib($this);
		
		if(!isset($this->_profiles[$profile->getIdentifier()])) {
			$this->_profiles[$profile->getIdentifier()] = $profile;	
		}
	}
	
		
	
	public function getProfile($identifier)
	{
			
		
		if($identifier instanceof Emerald_Filelib_FileItem) {
			$identifier = $identifier->profile;
		}
						
		if(!isset($this->_profiles[$identifier])) {
			throw new Emerald_Filelib_Exception("File profile '{$identifier}' not found");
		}
		
		return $this->_profiles[$identifier];
	}
	
	public function getProfiles()
	{
		return $this->_profiles;
	}
	
	
	
	public function file()
	{
		if(!$this->_fileOperator) {
			$this->_fileOperator = new Emerald_Filelib_FileOperator($this);
		}
		return $this->_fileOperator;
	}
	
	
	public function folder()
	{
		if(!$this->_folderOperator) {
			$this->_folderOperator = new Emerald_Filelib_FolderOperator($this);
		}
		
		return $this->_folderOperator;
	}
	
	
	
	
	/**
	 * Sets fileitem class
	 * 
	 * @param string $fileItemClass Class name
	 */
	public function setFileItemClass($fileItemClass)
	{
		$this->_fileItemClass = $fileItemClass;
	}
	
	
	/**
	 * Returns fileitem class
	 * 
	 * @return string
	 */
	public function getFileItemClass()
	{
		return $this->_fileItemClass;
	}
	
	
	/**
	 * Sets folderitem class
	 * 
	 * @param string $folderItemClass Class name
	 */
	public function setFolderItemClass($folderItemClass)
	{
		$this->_folderItemClass = $folderItemClass;
	}
	
	
	/**
	 * Returns folderitem class
	 * 
	 * @return string
	 */
	public function getFolderItemClass()
	{
		return $this->_folderItemClass;
	}
	
	
	/**
	 * Sets backend
	 * 
	 * @param Emerald_Filelib_Backend_Interface $handler 
	 */
	public function setBackend(Emerald_Filelib_Backend_Interface $backend)
	{
		$backend->setFilelib($this);
		$this->_backend = $backend;
	}
		
	
	/**
	 * Returns backend
	 * 
	 * @return Emerald_Filelib_Backend_Interface
	 */
	public function getBackend()
	{
		if(!$this->_backend) {
			throw new Emerald_Filelib_Exception('Filelib backend not set');
		}
		
		return $this->_backend;
	}

	
	/**
	 * Sets symbolic link from public to private root
	 * 
	 * @param string $relativePathToRoot 
	 * @return Emerald_Filelib Filelib
	 */
	public function setRelativePathToRoot($relativePathToRoot)
	{
		$this->_relativePathToRoot = $relativePathToRoot;
		return $this;
	}
	
	
	/**
	 * Returns symbolic link from public to private root
	 * 
	 * @return string
	 */
	public function getRelativePathToRoot()
	{
		return $this->_relativePathToRoot;
	}
	
	
	
	
	
	/**
	 * Adds a plugin
	 * 
	 * @param Emerald_Filelib_Plugin_Interface Plugin $plugin
	 * @return Emerald_Filelib Filelib
	 */
	public function addPlugin(Emerald_Filelib_Plugin_Interface $plugin)
	{
		$plugin->setFilelib($this);
		
		foreach($plugin->getProfiles() as $profileIdentifier) {
			$profile = $this->getProfile($profileIdentifier);
			$profile->addPlugin($plugin);			
		}
		
		$this->_plugins[] = $plugin;
		
		$plugin->init();
		
		return $this;
		
	}

	/**
	 * Returns all plugins
	 * 
	 * @return array Array of plugins
	 */
	public function getPlugins()
	{
		return $this->_plugins;
	}
	
	
	
	/**
	 * Sets files per directory
	 * 
	 * @param integer $filesPerDirectory
	 * @return Emerald_Filelib Filelib
	 */
	public function setFilesPerDirectory($filesPerDirectory)
	{
		$this->_filesPerDirectory = $filesPerDirectory;
	}
	
	
	
	/**
	 * Returns files per directory
	 * 
	 * @return integer
	 */
	public function getFilesPerDirectory()
	{
		return $this->_filesPerDirectory;
	}
	
	/**
	 * Sets levels per directory hierarchy
	 * 
	 * @param integer $directoryLevels
	 * @return Emerald_Filelib Filelib
	 */
	public function setDirectoryLevels($directoryLevels)
	{
		$this->_directoryLevels = $directoryLevels;
		return $this;
	}
	
	
	
	/**
	 * Returns levels in directory hierarchy
	 * 
	 * @return integer
	 */
	public function getDirectoryLevels()
	{
		return $this->_directoryLevels;
	}
	
	
	
	/**
	 * Sets directory permission
	 * 
	 * @param integer $directoryPermission
	 * @return Emerald_Filelib Filelib
	 */
	public function setDirectoryPermission($directoryPermission)
	{
		$this->_directoryPermission = octdec($directoryPermission);
		return $this;
	}
		
	
	/**
	 * Returns directory permission
	 * 
	 * @return integer
	 */
	public function getDirectoryPermission()
	{
		return $this->_directoryPermission;
	}
	
	/**
	 * Sets file permission
	 * 
	 * @param integer $filePermission
	 * @return Emerald_Filelib Filelib
	 */
	public function setFilePermission($filePermission)
	{
		$this->_filePermission = octdec($filePermission);
		return $this;
	}
	
	/**
	 * Returns file permission
	 * 
	 * @return integer
	 */
	public function getFilePermission()
	{
		return $this->_filePermission;
	}
	
	
	
	/**
	 * Returns directory identifier (path) for specified file id
	 * 
	 * @param integer $fileId File id
	 * @return string
	 */
	public function getDirectoryId($fileId)
	{

		$directoryLevels = $this->getDirectoryLevels() + 1;
		$filesPerDirectory = $this->getFilesPerDirectory(); 
		
		if($directoryLevels < 1) {
			throw new Emerald_Filelib_Exception("Invalid number of directory levels ('{$directoryLevels}')");
		}
		
		$arr = array();
		$tmpfileid = $fileId - 1;

		for($count = 1; $count <= $directoryLevels; ++$count) {
			$lus = $tmpfileid / pow($filesPerDirectory, $directoryLevels - $count);
			$tmpfileid = $tmpfileid % pow($filesPerDirectory, $directoryLevels - $count);
			$arr[] = floor($lus) + 1;
		}
		
		$puuppa = array_pop($arr);		
		return implode(DIRECTORY_SEPARATOR, $arr);
		
	}
	
	
	
	/**
	 * Sets magic file
	 * 
	 * @param string $magic Path to magic file
	 * @return Emerald_Filelib Filelib
	 */
	public function setMagic($magic)
	{
		$this->_magic = $magic;
		return $this;
	}
	
	
	/**
	 * Returns path to magic file
	 * 
	 * @return string
	 */
	public function getMagic()
	{
		return $this->_magic;
	}
	
	
	/**
	 * Sets root
	 * 
	 * @param string $root
	 * @return Emerald_Filelib Filelib
	 */
	public function setRoot($root)
	{
		$this->_root = $root;		
	}
	
	
	/**
	 * Returns root
	 * 
	 * @return string
	 */
	public function getRoot()
	{
		return $this->_root;
	}

	
	/**
	 * Sets web access prefix
	 * 
	 * @param string $publicDirectoryPrefix
	 * @return Emerald_Filelib Filelib
	 */
	public function setPublicDirectoryPrefix($publicDirectoryPrefix)
	{
		$this->_publicDirectoryPrefix = $publicDirectoryPrefix;
		return $this;				
	}
	
	
	/**
	 * Returns web access prefix
	 * 
	 * @return string
	 */
	public function getPublicDirectoryPrefix()
	{
		return $this->_publicDirectoryPrefix;
	}
		
		
	/**
	 * Sets public root
	 * 
	 * @param string $publicRoot 
	 * @return Emerald_Filelib Filelib
	 */
	public function setPublicRoot($publicRoot)
	{
		$this->_publicRoot = $publicRoot;
		return $this;				
	}
	
	
	/**
	 * Returns public root
	 * 
	 * @return string
	 */
	public function getPublicRoot()
	{
		return $this->_publicRoot;
	}


	/**
	 * Sets acl handler
	 * 
	 * @param Emerald_Filelib_Acl_Interface $acl
	 * @return Emerald_Filelib Filelib
	 */
	public function setAcl(Emerald_Filelib_Acl_Interface $acl)
	{
		$this->_acl = $acl;
		return $this;
	}
	
	
	/**
	 * Returns acl handler
	 * 
	 * @return Emerald_Filelib_Acl_Interface
	 */
	public function getAcl()
	{
		if(!$this->_acl) {
			$this->_acl = new Emerald_Filelib_Acl_Simple();
		}
		return $this->_acl;
	}
	
	
		
	
	
	
}
?>