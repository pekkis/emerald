<?php
class Emerald_Filelib
{

	/**
     * Get singleton
     *
     * @return Emerald_Filelib
     */
    public static function getInstance()
    {
        static $instance;
        if(!$instance) {
            $instance = new self();
        }
        return $instance;  
    }
	
	
	public function upload(Emerald_Filelib_FileUpload $fileUpload, Emerald_Db_Table_Row_Filelib_Folder $folder)
	{
		
		$emerald = Emerald_Application::getInstance();
		
		if(!$fileUpload->isUploadable()) {
			throw new Emerald_Filelib_BadMimeTypeException("Bad mime type '{$fileUpload->getMimeType()}'", 500);
		}

		
		$fileTbl = Emerald_Model::get('Filelib_File');

		$file = $fileTbl->createRow();
		
		
		try {
			
			$fileTbl->getAdapter()->beginTransaction();
			
			$file->folder_id = $folder->id;
			$file->mimetype = $fileUpload->getMimeType();
			$file->size = $fileUpload->getSize();
			$file->name = $fileUpload->getOverrideFilename();	

			$file->save();
			

			$root = $emerald->getCustomer()->getRoot();
			
			$dir = $root . '/files/' . ceil($file->id / 500); 
			
			if(!is_dir($dir)) {
				@mkdir($dir, 0700, true);
			}
						
			if(!is_dir($dir) || !is_writable($dir)) {
				throw new Emerald_Exception('Could not write into directory', 500);
			}
			
			$fileTarget = $dir . '/' . $file->id;
						
			copy($fileUpload->getRealPath(), $fileTarget);
			
			chmod($fileTarget, 0600);
			
			if(!is_readable($fileTarget)) {
				throw new Emerald_Exception('Could not copy file to folder');
			}
						
			$fileTbl->getAdapter()->commit();
			
		} catch(Exception $e) {
			
			$fileTbl->getAdapter()->rollBack();
			
			throw $e;
		}
		
		// Operation success. If anon group can read, make symlink to file's iisiurl.
		$acl = Emerald_Application::getInstance()->getAcl();
		if($acl->isAllowed('Emerald_Group_' . Emerald_Group::GROUP_ANONYMOUS, $folder, 'read')) {
			$file->createSymlink();
		}
		
		
		
		
		
	}
	
	
	public function delete(Emerald_Db_Table_Row_Filelib_File $file)
	{
						
		$file->getTable()->getAdapter()->beginTransaction();
		
		try {
			$path = Emerald_Application::getInstance()->getCustomer()->getRoot() . '/files/' . ceil($file->id / 500) . '/' . $file->id;
	
			$fileObj = new SplFileObject($path);
			
			
			if(!$fileObj->isFile() || !$fileObj->isWritable()) {
				
				
				
				throw new Emerald_Filelib_Exception('Can not delete file');
			}
			
			if(!@unlink($fileObj->getPathname())) {
				throw new Emerald_Filelib_Exception('Can not delete file');
			}
			
			$file->delete();
			
			$file->getTable()->getAdapter()->commit();
			
		} catch(Emerald_Filelib_Exception $e) {
			
			$file->getTable()->getAdapter()->rollback();
			throw $e;
			
		}
		
		
		return true;
		
	}
	
	
	
	
}
?>