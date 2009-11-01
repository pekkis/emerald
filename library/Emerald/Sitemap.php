<?php
class Emerald_Sitemap
{
	/**
	 * Sitemap locale
	 *
	 * @var String
	 */
	private $_locale;
	
	/**
	 * The userid used in selects (the user viewing the sitemap) to apply security
	 * 
	 * @var Integer
	 */
	private $_viewingUser;
	
	private $_db = NULL;
	
	// base query with a placeholder for joins and where
	private static $_baseQuery = "
	SELECT id, parent_id, order_id,locale,title,iisiurl,shard_id,created,modified,created_by,modified_by, status, visibility,
	(SELECT COUNT(*) FROM page as y WHERE y.parent_id = x.id) AS 'child_cnt'
	FROM page AS x %s WHERE %s GROUP BY id ORDER BY order_id ASC";
	// Just a remainder : (SELECT GROUP_CONCAT(ugroup_id,':',permission) FROM permission_page_ugroup WHERE page_id = id AND) AS group_permission
	
	
	/**
	 * Constructor
	 * 
	 * @param String $land locale identifier (fi, en whatever)
	 * @param Integer|NULL $userId Used in adding permission data to sitemap nodes, NOT USED currently
	 */
	public function __construct($lang, $userId = NULL)
	{
		$this->_locale = $lang;
		$this->_viewingUser = $userId;
		$this->_db = Zend_Registry::get('Emerald_Db');
	}

	/**
	 * Returns sitemap locale
	 *
	 * @return String
	 */
	public function getLocale()
	{
		return $this->_locale;
	}
	
	public function getHomeId()
	{
		static $homeId;
		if(!$homeId)	
			$homeId = (int)$this->_db->fetchOne("SELECT page_start FROM locale WHERE locale = ?", array($this->_locale));
		return $homeId;
	}
	
	/**
	 * Finds a page via id
	 *
	 * @param integer $id
	 * @return mixed Page or false
	 */
	public function findBranch($id)
	{
		$id = (int)$id;
	
		
		static $statement = Array();
		static $cache = Array();
		
		if(isset($cache[$this->_locale.$id])) return $cache[$this->_locale.$id];
		
		if(!isset($statement[$this->_locale]))
		{
			$where[] = "COALESCE(parent_id, 0) = ?";
			$where[] = "locale = '{$this->_locale}'";
			$whereSql = implode(" AND ", $where);

			$sql = sprintf(self::$_baseQuery, '', $whereSql);
			$statement[$this->_locale] = new Zend_Db_Statement_PDO($this->_db, $sql);
		}
		
		$branch = Array();
		$acl = Zend_Registry::get('Emerald_Acl');
		$user = Zend_Registry::get('Emerald_User');
		if($res = $statement[$this->_locale]->execute(array($id)))
		{
			while($data = $statement[$this->_locale]->fetch(Zend_Db::FETCH_OBJ))
			{
				$smNode = new Emerald_Sitemap_Node($data);
				$smNode->canRead = $acl->isAllowed($user, $smNode, "read") 		? 1 : 0;
				$smNode->canWrite = $acl->isAllowed($user, $smNode, "write") 	? 1 : 0;
				$smNode->isHome = $this->getHomeId() == $smNode->id	? 1 : 0;
				if($smNode->canRead) $branch[] = $smNode;
			}
			$cache[$this->_locale.$id] = $branch;
		}
		
		return $branch;
	}
	
}