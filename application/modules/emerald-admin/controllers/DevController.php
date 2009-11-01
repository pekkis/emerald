<?php
class Admin_DevController extends Emerald_Controller_AdminAction 
{

	
	
	public function recreatefileiisiurlsAction()
	{
		
		$fileTbl = Emerald_Model::get('Filelib_File');
				
		
		$files = $fileTbl->fetchAll();
		

		
		foreach($files as $file)
		{
			$file->save();
			
		}

		
		die('createeeed');
		
	}
	

	
	public function recreateiisiurlsAction()
	{
		$res = $this->_db->fetchCol("SELECT id FROM page");
		foreach($res as $id) {
			
			$page = Emerald_Page::find($id);
			// this is now done by magick(tm) ;)
			/*
			$page->iisiurl = $page->makeIisiUrl();
			
			$route = $page->getRoute('id');
			
			foreach($route as &$routePart) {
				$routePart = '[' . $routePart . ']'; 
			}
			
			$route = implode(';', $route);
			$page->path = $route;
			*/
			$page->save();
		}
		
		
		
		
		
		die('recreated iisiurls');
	}
	
	
	public function uploadAction()
	{
		$path = '/home/pekkis/Pictures/forsstrom.png';
		
		$file = new Emerald_Filelib_FileUpload($path);
				
		$folderTbl = Emerald_Model::get('Filelib_Folder');
			
		$folder = $folderTbl->fetchRow();
		
		$filelib = Emerald_Filelib::getInstance();
		
		try {
			$filelib->upload($file, $folder);	
		} catch(Emerald_Exception $e) {
			die('uplood failed...');
		}
		
		
		
		
		
		
		
		
		die('uplood suksee');
	}
	
	
	
	public function createfilesymlinksAction()
	{
		$fileTbl = Emerald_Model::get('Filelib_File');
		$files = $fileTbl->fetchAll();
		
		$acl = Zend_Registry::get('Emerald_Acl');
		
		foreach($files as $file)
		{
			$folder = $file->getFolder();

			if($acl->isAllowed('Emerald_Group_1', $folder, 'read')) {
				$file->createSymlink();	
			} else {
				$file->deleteSymlink();
			}
		}

		
		die('symlinkeeeeed');
		
		
	}
	
	
	public function deletefilesymlinksAction()
	{
		$fileTbl = Emerald_Model::get('Filelib_File');
		$files = $fileTbl->fetchAll();
		foreach($files as $file)
		{
			$file->deleteSymlink();
			
		}

		
		die('unsymlinkeeeeed');
		
		
	}
	
	
}
?>