<?php
class Filelib_Model_FileRow extends Zend_Db_Table_Row_Abstract
{
	
	private $_route;
	
	
	public function getPath()
	{
		$fl = Zend_Registry::get('Emerald_Filelib');
		return $fl->getRoot() . '/' .  $fl->getDirectoryId($this->id) . '/' . $this->id;
	}
	
	
	public function getRoute($specified = false)
	{
		if(!$this->_route) {

			$route = array('id' => array(), 'name' => array());

			$route['name'][] = $this->name;
			
			$file = $this;
			
			$folder = $myFolder = $this->findParentRow('Filelib_Model_DbTable_Folder');

			array_unshift($route['id'], $folder->id);
			array_unshift($route['name'], $folder->name);
					
			
			
			while($folder = $folder->findParent()) {
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
		$fl = Zend_Registry::get('Emerald_Filelib');
		
		$link = $fl->getPublicRoot() . '/' . $this->iisiurl;
						
		if(!is_link($link)) {

			$path = dirname($link);
		
			if(!is_dir($path))
				mkdir($path, 0700, true);

			symlink($this->getPath(), $link);
			
		}
		
	}
	
	
	public function deleteSymlink()
	{
		$fl = Zend_Registry::get('Emerald_Filelib');
		$link = $fl->getPublicRoot() . '/' . $this->iisiurl;
		if(is_link($link)) {
			unlink($link);
		}
	}
	
	
}
?>