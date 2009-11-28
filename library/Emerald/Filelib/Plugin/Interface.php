<?php
interface Emerald_Filelib_Plugin_Interface
{
	
	public function init();
		
	public function beforeUpload(Emerald_FileObject $upload);
	
	public function afterUpload(Emerald_Filelib_FileItem $file);
	
	public function beforeDelete(Emerald_Filelib_FileItem $file);
	
	public function afterDelete(Emerald_Filelib_FileItem $file);
	
	public function createSymlink(Emerald_Filelib_FileItem $file);
	
	public function deleteSymlink(Emerald_Filelib_FileItem $file);
	
}