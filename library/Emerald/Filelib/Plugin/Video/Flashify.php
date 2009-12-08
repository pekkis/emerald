<?php
class Emerald_Filelib_Plugin_Video_Flashify
extends Emerald_Filelib_Plugin_VersionProvider_Abstract
{

	public function init()
	{
		// Register a version for images with identifier and self as provider.
		$this->getFilelib()->addFileVersion('video', $this->getIdentifier(), $this);
	}
	
	
	/**
	 * Creates version. Potentially overwrites old one.
	 * 
	 * @param Emerald_FileItem $file
	 */
	public function createVersion(Emerald_Filelib_FileItem $file)
	{
		if($file->getType() != 'video') {
			throw new Exception('File must be an video');
		}

		$path = $file->getPath() . '/' . $this->getIdentifier();
		
		if(!is_dir($path)) {
			mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
		}
											
   		$exec_string = "/usr/bin/ffmpeg -i {$file->getPathname()} -f flv {$path}/{$file->id}";
   		
   		exec($exec_string); //where exxc is the command used to execute shell comma
		
	}
	
	
	public function afterUpload(Emerald_Filelib_FileItem $file)
	{
								
		if($file->getType() == 'video') {
			$this->createVersion($file);
		}
	}
	
	
	public function createSymlink(Emerald_Filelib_FileItem $file)
	{
		if($file->getType() == 'video') {
			$fl = $this->getFilelib();
			$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier();

			
			$link .= '.flv';	
			
						
			if(!is_link($link)) {

				$path = dirname($link);
				if(!is_dir($path)) {
					mkdir($path, $this->getFilelib()->getDirectoryPermission(), true);
				}
								
				if($fl->getRelativePathToRoot()) {
										
					// Relative linking requires some movin'n groovin.
					$oldCwd = getcwd();
					chdir($path);
					
					$fp = dirname($this->getFilelib()->getSymlinker()->getLinkSource($file, 1));
					$fp .= '/' . $this->getIdentifier() . '/' . $file->id;
					
					symlink($fp, $link);
					
					chdir($oldCwd);
				
				} else {
					symlink($file->getPath() . '/' . $this->getIdentifier() . '/' . $file->id, $link);
				}
				
			}
		}
	}
	
	public function deleteSymlink(Emerald_Filelib_FileItem $file)
	{
		$fl = $this->getFilelib();
		$link = $fl->getPublicRoot() . '/' . $file->iisiurl;
		$pinfo = pathinfo($link);
		$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier();

		$link .= '.flv';
				
		if(is_link($link)) {
			unlink($link);			
		}
		
	}
	
	
	
	public function getRenderPath(Emerald_Filelib_FileItem $file)
	{
		if($file->isAnonymous()) {

			$fl = $this->getFilelib();
			$link = $fl->getPublicDirectoryPrefix() . '/' . $file->iisiurl;
			$pinfo = pathinfo($link);
			$link = $pinfo['dirname'] . '/' . $pinfo['filename'] . '-' . $this->getIdentifier() . '.' . $pinfo['extension'];

			return $link;
			
		} else {
			$path = $file->getPath() . '/' . $this->getIdentifier() . '/' . $file->id;	
		}
				
		return $path;		
	}
	
	
}
?>