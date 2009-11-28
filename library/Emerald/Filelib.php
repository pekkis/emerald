<?php
class Emerald_Filelib
{
	private $_handler;
		
	private $_acl;
	
	private $_root;
	
	private $_publicRoot;
	
	private $_publicDirectoryPrefix = '';
	
	private $_magic;

	private $_plugins = array();


	
	
	private $_filesPerDirectory = 500;
	
	private $_directoryPermission = 0700;
	private $_filePermission = 0600;
	
	private $_symlinker;

	
	private $_fileVersions;
	
	public function __construct($options = array())
	{
		Emerald_Options::setConstructorOptions($this, $options);
	}
	
	
	
	public function setHandler(Emerald_Filelib_Handler_Interface $handler)
	{
		$handler->setFilelib($this);
		$this->_handler = $handler;
	}
	
	
	public function getHandler()
	{
		if(!$this->_handler) {
			throw new Emerald_Filelib_Exception('Filelib handler not set');
		}
		
		return $this->_handler;
	}
	
	
	
	
	/**
	 * @return Emerald_Filelib_Symlinker
	 */
	public function getSymlinker()
	{
		if(!$this->_symlinker) {
			$this->_symlinker = new Emerald_Filelib_Symlinker($this);
		}
		return $this->_symlinker;
	}
	
	
	public function addPlugin(Emerald_Filelib_Plugin_Abstract $plugin)
	{
		$plugin->setFilelib($this);
		$this->_plugins[] = $plugin;
		
		$plugin->init();
		
	}

	public function getPlugins()
	{
		return $this->_plugins;
	}
	
	
	
	public function addFileVersion($fileType, $versionIdentifier, $versionProvider)
	{
		if(!isset($this->_fileVersions[$fileType])) {
			$this->_fileVersions[$fileType] = array();
		}		
		$this->_fileVersions[$fileType][$versionIdentifier] = $versionProvider; 
	}
	
	
	
	
	public function getFilesPerDirectory()
	{
		return $this->_filesPerDirectory;
	}
	
	public function setDirectoryPermission($directoryPermission)
	{
		$this->_directoryPermission = octdec($directoryPermission);
	}
	
	
	
	public function getDirectoryPermission()
	{
		return $this->_directoryPermission;
	}
	
	
	public function setFilePermission($filePermission)
	{
		$this->_filePermission = octdec($filePermission);
	}
	
	
	public function getFilePermission()
	{
		return $this->_filePermission;
	}
	
	
	public function getDirectoryId($fileId)
	{
		return ceil($fileId / $this->getFilesPerDirectory());	
	}
	
	
	
	public function setMagic($magic)
	{
		$this->_magic = $magic;
	}
	
	
	public function getMagic()
	{
		return $this->_magic;
	}
	
	
	public function setRoot($root)
	{
		$this->_root = $root;		
	}
	
	
	public function getRoot()
	{
		return $this->_root;
	}

	
	public function setPublicDirectoryPrefix($publicDirectoryPrefix)
	{
		$this->_publicDirectoryPrefix = $publicDirectoryPrefix;				
	}
	
	
	public function getPublicDirectoryPrefix()
	{
		return $this->_publicDirectoryPrefix;
	}
		
		
	public function setPublicRoot($publicRoot)
	{
		$this->_publicRoot = $publicRoot;				
	}
	
	
	public function getPublicRoot()
	{
		return $this->_publicRoot;
	}
		
	
	


	public function setAcl(Emerald_Filelib_Acl_Interface $acl)
	{
		$this->_acl = $acl;
	}
	
	
	/**
	 * @return Emerald_Filelib_Acl_Interface
	 */
	public function getAcl()
	{
		if(!$this->_acl) {
			$this->_acl = new Emerald_Filelib_Acl_Simple();
		}
		return $this->_acl;
	}
	
		
	public function findFile($id)
	{
		$file = $this->getHandler()->findFile($id);
		$file->setFilelib($this);
		return $file;
		
	}
	
	
	public function findFolder($id)
	{
		$folder = $this->getHandler()->findFolder($id);
		$folder->setFilelib($this);
		return $folder;
	}

	
	public function findFilesIn(Emerald_Filelib_FolderItem $folder)
	{
		$items = $this->getHandler()->findFilesIn($folder);
		foreach($items as $item) {
			$item->setFilelib($this);
		}
		
		return $items;
	}
	
	
	
	
	/**
	 * @param $file
	 * @return unknown_type
	 */
	public function fileIsAnonymous($file)
	{
		return true;
	}
		
	
	public function getUpload($path)
	{
		$upload = new Emerald_FileObject($path);
		$upload->setFilelib($this);
		return $upload;		
	}
	
	
	public function upload($upload, $folder)
	{
		if(!$upload instanceof Emerald_FileObject) {
			$upload = new Emerald_FileObject($upload);
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
				
		$file = $this->getHandler()->upload($upload, $folder);
		$file->setFilelib($this);

		if(!$file) {
			throw new Emerald_Filelib_Exception("Can not upload");
		}
		
		foreach($this->getPlugins() as $plugin) {
			$plugin->setFile($file);
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
			$upload = $plugin->afterUpload();
		}
				
		if($this->getAcl()->isAnonymousReadable($file)) {
			$this->getSymlinker()->deleteSymlink($file);
			$this->getSymlinker()->createSymlink($file);			
		}
		
		return $file;
	}
	
	
	public function deleteFile(Emerald_Filelib_FileItem $file)
	{
		try {

			$this->getHandler()->deleteFile($file);
			$this->getSymlinker()->deleteSymlink($file);
						
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

			echo $e;
			
			return true;
			
			// $this->getDb()->rollBack();
			throw $e;
		}
		
		
		return true;
		
		
		
	}
	
	
	public function getFileType(Emerald_Filelib_FileItem $file)
	{
		// Mock until mimetype database is pooped in.
		$split = explode('/', $file->mimetype);
		return $split[0];
	}
	
	
	public function fileHasVersion(Emerald_Filelib_FileItem $file, $version)
	{
		$filetype = $this->getFileType($file);
		if(isset($this->_fileVersions[$filetype][$version])) {
			return true;
		}
		return false;
		
	}
	
	public function getVersionProvider(Emerald_Filelib_FileItem $file, $version)
	{
		$filetype = $this->getFileType($file);
		
		return $this->_fileVersions[$filetype][$version];
		
	}
	
	
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
						
		return;
		
	}
	
	
	
	
}
?>