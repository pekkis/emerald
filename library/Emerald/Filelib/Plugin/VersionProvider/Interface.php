<?php
/**
 * Interface for version providing plugins
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface Emerald_Filelib_Plugin_VersionProvider_Interface
{
	public function setIdentifier($identifier);
	
	public function getIdentifier();
	
	public function getRenderPath(Emerald_Filelib_FileItem $file);
	
	public function createVersion(Emerald_Filelib_FileItem $file);
}
