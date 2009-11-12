<?php
interface Filelib_Model_Plugin_Interface
{
	
	public function beforeUpload(Filelib_Model_Upload $upload);
	
	public function afterUpload();
	
	public function beforeDelete();
	
	public function afterDelete();
	
	public function createSymlink();
	
	public function deleteSymlink();
	
}