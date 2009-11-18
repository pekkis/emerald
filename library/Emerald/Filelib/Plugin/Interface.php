<?php
interface Emerald_Filelib_Plugin_Interface
{
	
	public function beforeUpload(Emerald_FileObject $upload);
	
	public function afterUpload();
	
	public function beforeDelete();
	
	public function afterDelete();
	
	public function createSymlink();
	
	public function deleteSymlink();
	
}