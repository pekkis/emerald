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
	 * @var Emerald_Filelib_Backend_Interface Backend handler
	 */
	private $_handler;
		
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
	 * @var integer Octal representation for directory permissions
	 */
	private $_directoryPermission = 0700;

	/**
	 * @var integer Octal representation for file permissions
	 */
	private $_filePermission = 0600;
	
	/**
	 * @var Emerald_Filelib_Symlinker Symlinker
	 */
	private $_symlinker;
	
	/**
	 * @var array Versions for file types
	 */
	private $_fileVersions = array();

	
	public function __construct($options = array())
	{
		Emerald_Options::setConstructorOptions($this, $options);
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
	 * Returns symlinker
	 * 
	 * @return Emerald_Filelib_Symlinker_Interface
	 */
	public function getSymlinker()
	{
		if(!$this->_symlinker) {
			throw new Emerald_Filelib_Exception("Filelib must have a symlinker");
		}
		return $this->_symlinker;
	}
	
	
	/**
	 * Sets symlinker
	 * 
	 * @param Emerald_Filelib_Symlinker_Interface|string $symlinker
	 * @return Emerald_Filelib Filelib
	 */
	public function setSymlinker($symlinker)
	{
		if(!$symlinker instanceof Emerald_Filelib_Interface) {
			$symlinker = new $symlinker($this); 			
		}
		
		$this->_symlinker = $symlinker;
		
		return $this;
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
	 * Adds a file version
	 * 
	 * @param string $fileType string File type
	 * @param string $versionIdentifier Version identifier
	 * @param object $versionProvider Version provider reference
	 * @return Emerald_Filelib Filelib
	 */
	public function addFileVersion($fileType, $versionIdentifier, $versionProvider)
	{
		if(!isset($this->_fileVersions[$fileType])) {
			$this->_fileVersions[$fileType] = array();
		}		
		$this->_fileVersions[$fileType][$versionIdentifier] = $versionProvider;

		return $this;
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
	 * Returns directory id for specified file id
	 * 
	 * @param integer $fileId File id
	 * @return integer
	 */
	public function getDirectoryId($fileId)
	{
		return ceil($fileId / $this->getFilesPerDirectory());	
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
	
	
	
	
	/**
	 * Creates a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder
	 * @return unknown_type
	 */
	public function createFolder(Emerald_Filelib_FolderItem $folder)
	{
		return $this->getBackend()->createFolder($folder);
	}
	
	
	/**
	 * Deletes a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder Folder
	 */
	public function deleteFolder(Emerald_Filelib_FolderItem $folder)
	{
		return $this->getBackend()->deleteFolder($folder);
	}
	
	/**
	 * Updates a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder Folder
	 */
	public function updateFolder(Emerald_Filelib_FolderItem $folder)
	{
		return $this->getBackend()->updateFolder($folder);
	}
	
	
	/**
	 * Updates a file
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 * @return unknown_type
	 */
	public function updateFile(Emerald_Filelib_FileItem $file)
	{
		return $this->getBackend()->updateFile($file);
	}
		
		
	/**
	 * Finds a file
	 * 
	 * @param mixed $idFile File id
	 * @return Emerald_Filelib_FileItem
	 */
	public function findFile($id)
	{
		$file = $this->getBackend()->findFile($id);
		$file->setFilelib($this);
		return $file;
		
	}
	
	/**
	 * Finds and returns all files
	 * 
	 * @return Emerald_Filelib_FileItemIterator
	 */
	public function findAllFiles()
	{
		$items = $this->getBackend()->findAllFiles();
		foreach($items as $item) {
			$item->setFilelib($this);
		}
		return $items;
	}
	
	
	
	/**
	 * Finds a folder
	 * 
	 * @param mixed $id Folder id
	 * @return Emerald_Filelib_FolderItem
	 */
	public function findFolder($id)
	{
		$folder = $this->getBackend()->findFolder($id);
		$folder->setFilelib($this);
		return $folder;
	}
	
	/**
	 * Finds subfolders
	 * 
	 * @param Emerald_Fildlib_FolderItem $folder Folder
	 * @return Emerald_Filelib_FolderItemIterator
	 */
	public function findSubFolders(Emerald_Filelib_FolderItem $folder)
	{
		$folders = $this->getBackend()->findSubFolders($folder);
		foreach($folders as $folder) {
			$folder->setFilelib($this);
		}
		return $folders;
	}

	
	/**
	 * @param Emerald_Filelib_FolderItem $folder Folder
	 * @return Emerald_Filelib_FileItemIterator Collection of file items
	 */
	public function findFilesIn(Emerald_Filelib_FolderItem $folder)
	{
		$items = $this->getBackend()->findFilesIn($folder);
		foreach($items as $item) {
			$item->setFilelib($this);
		}
		
		return $items;
	}
	
	
	/**
	 * Returns whether a file is anonymous
	 * 
	 * @todo This is still mock!
	 * @param Emerald_Filelib_FileItem $file File
	 * @return boolean
	 */
	public function fileIsAnonymous(Emerald_Filelib_FileItem $file)
	{
		return true;
	}
		
	
	/**
	 * Gets a new upload
	 * 
	 * @param string $path Path to upload file
	 * @return Emerald_Filelib_FileUpload
	 */
	public function getUpload($path)
	{
		$upload = new Emerald_Filelib_FileUpload($path);
		$upload->setFilelib($this);
		return $upload;		
	}
	
	
	/**
	 * Uploads file to filelib.
	 * 
	 * @param mixed $upload Uploadable, path or object
	 * @param Emerald_Filelib_FolderItem $folder 
	 * @return Emerald_Filelib_FileItem
	 * @throws Emerald_Filelib_Exception
	 */
	public function upload($upload, $folder)
	{
		if(!$upload instanceof Emerald_Filelib_FileUpload) {
			$upload = $this->getUpload($upload);
		}
		
		if(!$this->getAcl()->isWriteable($folder)) {
			throw new Emerald_Filelib_Exception("Folder '{$folder->id}'not writeable");
		}
				
		if(!$upload->canUpload()) {
			throw new Emerald_Filelib_Exception("Can not upload");
		}
		
		foreach($this->getPlugins() as $plugin) {
			$upload = $plugin->beforeUpload($upload);
		}					
				
		$file = $this->getBackend()->upload($upload, $folder);
		$file->setFilelib($this);

		if(!$file) {
			throw new Emerald_Filelib_Exception("Can not upload");
		}
		

		try {
			$root = $this->getRoot();
			$dir = $root . '/' . $this->getDirectoryId($file->id); 

			if(!is_dir($dir)) {
				@mkdir($dir, $this->getDirectoryPermission(), true);
			}
						
			if(!is_dir($dir) || !is_writable($dir)) {
				throw new Emerald_Filelib_Exception('Could not write into directory', 500);
			}
			
			$fileTarget = $dir . '/' . $file->id;

			copy($upload->getRealPath(), $fileTarget);
			chmod($fileTarget, $this->getFilePermission());
			
			if(!is_readable($fileTarget)) {
				throw new Emerald_Filelib_Exception('Could not copy file to folder');
			}
								
		} catch(Exception $e) {
			// Maybe log here?
			throw $e;
		}

		foreach($this->getPlugins() as $plugin) {
			$upload = $plugin->afterUpload($file);
		}
				
		if($this->getAcl()->isAnonymousReadable($file)) {
			$this->getSymlinker()->deleteSymlink($file);
			$this->getSymlinker()->createSymlink($file);			
		}
		
		return $file;
	}
	
	
	/**
	 * Deletes a file
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 * @throws Emerald_Filelib_Exception 
	 */
	public function deleteFile(Emerald_Filelib_FileItem $file)
	{
		try {

			$this->getBackend()->deleteFile($file);
			$this->getSymlinker()->deleteSymlink($file);

			foreach($this->getPlugins() as $plugin) {
				if($plugin instanceof Emerald_Filelib_Plugin_VersionProvider_Interface && $plugin->providesFor($file)) {
					$plugin->deleteVersion($file);					
				}
			}
			
			
			$path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $file->id; 
							
			$fileObj = new SplFileObject($path);
			if(!$fileObj->isFile() || !$fileObj->isWritable()) {
				throw new Emerald_Filelib_Exception('Can not delete file');
			}
			
			if(!@unlink($fileObj->getPathname())) {
				throw new Emerald_Filelib_Exception('Can not delete file');
			}
			
			return true;
			
		} catch(Exception $e) {
			throw new Emerald_Filelib_Exception($e->getMessage());
		}
		
	}
	
	
	/**
	 * Returns file type of a file
	 * 
	 * @param Emerald_Filelib_FileItem File $file item
	 * @return string File type
	 */
	public function getFileType(Emerald_Filelib_FileItem $file)
	{
		// @todo Semi-mock until mimetype database is pooped in.
		$split = explode('/', $file->mimetype);
		return $split[0];
	}
	
	
	/**
	 * Returns whether a file has a certain version
	 * 
	 * @param Emerald_Filelib_FileItem $file File item
	 * @param string $version Version
	 * @return boolean
	 */
	public function fileHasVersion(Emerald_Filelib_FileItem $file, $version)
	{
		$filetype = $this->getFileType($file);
		if(isset($this->_fileVersions[$filetype][$version])) {
			return true;
		}
		return false;
		
	}
	
	
	/**
	 * Returns version provider for a file/version
	 * 
	 * @param Emerald_Filelib_FileItem $file File item
	 * @param string $version Version
	 * @return object Provider
	 */
	public function getVersionProvider(Emerald_Filelib_FileItem $file, $version)
	{
		$filetype = $this->getFileType($file);
		return $this->_fileVersions[$filetype][$version];
	}
	
	
	/**
	 * Renders a file's path
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 * @param array $opts Options
	 * @return string File path
	 */
	public function renderPath(Emerald_Filelib_FileItem $file, $opts = array())
	{
		if(isset($opts['version'])) {

			$version = $opts['version'];
									
			if($this->fileHasVersion($file, $version)) {
				$provider = $this->getVersionProvider($file, $version);
				$path = $provider->getRenderPath($file); 
			} else {
				throw new Emerald_Filelib_Exception("Version '{$version}' is not available");
			}
		} else {
			$path = $file->getRenderPath();	
		}

		return $path;
		
	}
	
	
	
	/**
	 * Renders a file to a response
	 * 
	 * @param Emerald_Filelib File $file item
	 * @param Zend_Controller_Response_Http $response Response
	 * @param array $opts Options
	 */
	public function render(Emerald_Filelib_FileItem $file, Zend_Controller_Response_Http $response, $opts = array())
	{
		$path = $this->renderPath($file, $opts);
		
		if($this->fileIsAnonymous($file)) {
			return $response->setRedirect($path, 302);
		}
		
		if(isset($opts['download'])) {
			$response->setHeader('Content-disposition', "attachment; filename={$file->name}");
		}
		
		if(!is_readable($path)) {
			throw new Model_Filelib_Exception('File not readable');
		}

		$response->setHeader('Content-Type', $file->mimetype);
		
		readfile($path);
		
	}
	
	
	
	
}
?>