<?php
class Emerald_Db_Table_Row_Filelib_File extends Zend_Db_Table_Row_Abstract
{
	
	private $_route;
	
	public function getFolder()
	{
		$folderTbl = Emerald_Model::get('Filelib_Folder');
		return $folderTbl->find($this->folder_id)->current();
	}
	
	
	public function getPath()
	{
		return Zend_Registry::get('Emerald_Customer')->getRoot() . '/files/' .  ceil($this->id / 500) . '/' . $this->id;
	}
	
	
	public function getRoute($specified = false)
	{
		if(!$this->_route) {

			$route = array('id' => array(), 'name' => array());

			$route['name'][] = $this->name;
			
			$file = $this;
			
			$folder = $myFolder = $this->getFolder();

			array_unshift($route['id'], $folder->id);
			array_unshift($route['name'], $folder->name);
					
			
			
			while($folder = $folder->getParent()) {
				array_unshift($route['id'], $folder->id);
				array_unshift($route['name'], $folder->name);
						
			}
			$this->_route = $route;
		}
				
		if($specified == 'id') {
			return $this->_route['id'];
		} elseif($specified == 'title') {
			return $this->_route['name'];
		} elseif($specified == 'link') {
			return $this->_route['link'];
		} elseif($specified == 'iisiurl') {
			$route = $this->_route['name'];
			return $route;
		}
			
		return $this->_route;
	}
	
	
	
	
	public function makeIisiUrl()
	{
		$routeArr = $this->getRoute('iisiurl');
		
		$locale = array_shift($routeArr);
		
		foreach($routeArr as &$route) {
			$route = mb_strtolower($route, 'utf8');
		}
		
		array_unshift($routeArr, $locale);
		
		$route = implode('/', $routeArr);
		return $route;
	}

	/**
	 * Some magick(tm) before insertions
	 */ 
	protected function _insert()
	{
		$this->_generateIisiUrl();
		return parent::_insert();
	}
	
	/**
	 * Some magick(tm) before updates
	 */ 
	protected function _update()
	{
		$this->_generateIisiUrl();
		return parent::_update();
	}
	
	/**
	 * Sets all IisiUrl(tm) related fields
	 */ 
	private function _generateIisiUrl()
	{
		
		$this->iisiurl = $this->makeIisiUrl();
		
		$route = $this->getRoute('id');
		
		foreach($route as &$routePart) {
			$routePart = '[' . $routePart . ']'; 
		}
		
		$route = implode(';', $route);
		$this->path = $route;
	}
	
	
	public function getResourceId()
	{
		return 'Emerald_Page_' . $this->id;
	}
	
	
	
	public function createSymlink()
	{
		$link = Zend_Registry::get('Emerald_Customer')->getRoot() . '/data/files/' . $this->iisiurl;
		
		
		if(!is_link($link)) {

			$path = dirname($link);
		
			if(!is_dir($path))
				mkdir($path, 0700, true);

			symlink($this->getPath(), $link);
				
			
			
			
		}
		
	}
	
	
	public function deleteSymlink()
	{
		$link = Zend_Registry::get('Emerald_Customer')->getRoot() . '/data/files/' . $this->iisiurl;
		if(is_link($link)) {
			unlink($link);
		}
		
	}
	
	
}
?>