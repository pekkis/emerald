<?php
/**
 * Decorates raw page table row with mo' functionality
 * 
 * Page model returns instances of Emerald_Page
 *
 */
class Emerald_Page extends Zend_Db_Table_Row_Abstract implements Zend_Acl_Resource_Interface
{
	
	const STATUS_HIDDEN = 1;
	
	
	/**
	 * Pages locale
	 *
	 * @var Zend_Locale
	 */
	private $_locale;
	
	
	
	/**
	 * Route to root
	 *
	 * @var array
	 */
	private $_route;
	
	
	
	public function init()
	{
		$acl = Zend_Registry::get('Emerald_Acl');
		if(!$acl->has($this)) {
			$db = $this->_getTable()->getAdapter();
			$acl->add($this);
	       	$sql = "SELECT ugroup_id, permission FROM permission_page_ugroup WHERE page_id = ?";
	       	$res = $db->fetchAll($sql, $this->id);
	       	foreach($res as $row) {
	       		foreach(Emerald_Permission::getAll() as $key => $name) {
	       			if($key & $row->permission) {
	       				$role = "Emerald_Group_{$row->ugroup_id}";
	       				if($acl->hasRole($role)) {
	       					$acl->allow($role, $this, $name);	
	       				}
	       				
	       			}
	       		}
	       	}
		}
	}
	
	
	
	/**
	 * Finds a page via id
	 *
	 * @param integer $id
	 * @return mixed Page or false
	 */
	static public function find($id)
	{
		$res = Emerald_Model::get('DbTable_Page')->find($id);
		if($row = $res->current())
			return $row;		
		return false;
	}
	
	
	static public function findByIisiUrl($iisiUrl)
	{
		$where = array(
			'iisiurl = ?' => $iisiUrl 
		);
		$res = Emerald_Model::get('DbTable_Page')->fetchAll($where);
		return $res->current() ? $res->current() : false;
	}

	
	/**
	 * Returns pages locale
	 *
	 * @return Zend_Locale
	 */
	public function getLocale()
	{
		if(!$this->_locale)
			$this->_locale = new Zend_Locale($this->locale);
	
		return $this->_locale;
	}
	
	
	public function getParent()
	{
		if(!$this->parent_id)
		{
			return false;
		}
		return self::find($this->parent_id);
	}

	public function getBranch($id = null)
	{
		$id = $id ? $id : $this->parent_id;
		$res = Emerald_Model::get('DbTable_Page')->findBranch($id, $this->locale);
		$branch = Array();
		while($row = $res->current())
		{
			$branch[] = $row;
			$res->next();
		}		
		return $branch;
	}
	public function getChildren()
	{
		return $this->getBranch($this->id);
	}
	
	public function getRoute($specified = false)
	{
		if(!$this->_route) {

			$route = array('id' => array(), 'title' => array(), 'link' => array());
	
			$route['id'][] = $this->id;
			$route['title'][] = $this->title;
			$route['link'][] = '/page/view/id/' . $this->id;
			
			$page = $this;
			while($page = $page->getParent()) {
				array_unshift($route['id'], $page->id);
				array_unshift($route['title'], $page->title);
				array_unshift($route['link'], '/page/view/id/' . $page->id);						
			}
			$this->_route = $route;
		}
				
		if($specified == 'id') {
			return $this->_route['id'];
		} elseif($specified == 'title') {
			return $this->_route['title'];
		} elseif($specified == 'link') {
			return $this->_route['link'];
		} elseif($specified == 'iisiurl') {
			$route = $this->_route['title'];
			array_unshift($route, $this->locale);
			return $route;
		}
			
		return $this->_route;
	}
	

	
	public function makeIisiUrl()
	{
		$routeArr = $this->getRoute('iisiurl');
		
		$locale = array_shift($routeArr);
		
		foreach($routeArr as &$route) {
			$route = Emerald_Iisiurl_Generator::getInstance()->generate($route, $this->getLocale()->getLanguage()); 
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
	
	protected function _postUpdate()
	{
		/** @var boolean $processing For preventing recursion ( save() below also calls postupdate ) */
		static $processing = false;
		if($processing === false)
		{
			$processing = true;
			$res = Emerald_Model::get('DbTable_Page')->fetchAll("path like '{$this->path};%'");
			foreach($res as $page)
			{
				$page->_generateIisiUrl();
				$page->save();
			}
			
			$processing = false;
		}
	}
	
	/**
	 * Sets all IisiUrl(tm) related fields
	 * 
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
	
	public function isSubpageOf($parentPage)
	{
		$page = $this;
		while($page = $page->getParent())
		{
			if($page->id == $parentPage->id) return true;
		}
		return false;
	}
	
	
	
	public function assertWritable(Emerald_User $user = null)
	{
		if(!$user)
			$user = Zend_Registry::get('Emerald_User');
			
		if(!Zend_Registry::get('Emerald_Acl')->isAllowed($user, $this, 'write'))
			throw new Emerald_Acl_ForbiddenException('Forbidden', 401);
		
	}
	
	
	public function assertReadable(Emerald_User $user = null)
	{
		if(!$user)
			$user = Zend_Registry::get('Emerald_User');
		
		if(!Zend_Registry::get('Emerald_Acl')->isAllowed($user, $this, 'read'))
			throw new Emerald_Acl_ForbiddenException('Forbidden', 401);
			
	}
	
	
	public function __toString()
	{
		return $this->id;
	}
	
	
	public function getLayout($action)
	{
		require Zend_Registry::get('Emerald_Customer')->getRoot() . '/views/scripts/layouts/Default.php';				
		$tpl = new Emerald_Layout_Default($this, $action);
		return $tpl;
	}
	
	
}
?>