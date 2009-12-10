<?php
interface Emerald_Filelib_Symlinker_Interface
{
	
	public function __construct(Emerald_Filelib $filelib);
	
	public function getFilelib();
	
	public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $prefix = true);

	public function getLink(Emerald_Filelib_FileItem $file, $prefix = true);
	
	public function createSymlink(Emerald_Filelib_FileItem $file);
	
	public function deleteSymlink(Emerald_Filelib_FileItem $file);
	
	public function getRelativePathTo(Emerald_Filelib_FileItem $file, $levelsDown = 0);
	
	
}