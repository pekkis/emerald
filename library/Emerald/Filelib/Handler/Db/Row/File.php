<?php
class Emerald_Filelib_Handler_Db_Row_File extends Zend_Db_Table_Row_Abstract implements Zend_Acl_Resource_Interface
{
	
	private $_route;
	
	
	public function getFilelib()
	{
		return Zend_Registry::get('Emerald_Filelib');
	}
	
	
	public function getResourceId()
	{
		return 'Emerald_Filelib_File_' . $this->id;
	}
	
	
	public function getPath()
	{
		$fl = $this->getFilelib();
		return $fl->getRoot() . '/' .  $fl->getDirectoryId($this->id);
	}
	
	
	public function getPathname()
	{
		$fl = $this->getFilelib();
		return $this->getPath() . '/' . $this->id;
	}
	
	
	public function getRoute($specified = false)
	{
		if(!$this->_route) {

			$route = array('id' => array(), 'name' => array());

			$route['name'][] = $this->name;
			
			$file = $this;
			
			$folder = $myFolder = $this->findParentRow('Emerald_Filelib_DbTable_Folder');

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
	}
	
	/**
	 * Some magick(tm) before updates
	 */ 
	protected function _update()
	{
		$this->_generateIisiUrl();
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
	
	
	
}
?>