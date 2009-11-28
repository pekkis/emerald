<?php
class Admin_SitemapController extends Emerald_Controller_AdminAction
{
	/**
	 * Displays the sitemap page tpl
	 */
	public function indexAction()
	{
		$validators = array(
			'locale' => Array
			(
				'allowEmpty' => false,
				'presence' => 'optional'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		$localeTbl = Emerald_Model::get('Locale');
		$this->view->locales = $locales = $localeTbl->fetchAll();
		
		if(!$locales->current()) {
			$this->getResponse()->setRedirect('/admin/locale');
			return;
		}
		
		$this->view->locale = $currLocale = Zend_Registry::get('Zend_Locale');	

		if(!$filtered->locale)
		{
			$this->_redirect("/admin/sitemap/index/locale/".$locales->current()->locale);
		}
		
		$this->view->editlocale = $locales->current()->locale;//count($locales) ? $locales->current()->locale : $currLocale->toString();
		foreach($locales as $lc)
		{
			if($lc->locale == $filtered->locale) $this->view->editlocale = $filtered->locale;
		}
		$this->view->workLocale = new Zend_Locale();
		// = $selectedLocales;
		$this->view->headScript()->appendFile("/lib/js/form.js");
		$this->view->headScript()->appendFile("/lib/js/admin/datasource.js");
		$this->view->headScript()->appendFile("/lib/js/admin/sitemap/index.js");
		$this->view->headLink()->appendStylesheet("/lib/css/admin/sitemap/sitemap.css");
	}
	
	/**
	 * Returns the childs of given node in json format
	 * 
	 * PERMISSIONS: only pages with read permission are included in the list
	 */
	public function branchAction()
	{
		$validators = array(
			'start' => Array
			(
				'Int',
				'allowEmpty' => true,
				'presence' => 'required'
			),
			'locale' => Array
			(
				'allowEmpty' => false,
				'presence' => 'optional'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		$locale = $filtered->locale ? $filtered->locale : Zend_Registry::get('Zend_Locale')->toString();
		
		$parentId = $filtered->start;
		$sitemap = new Emerald_Sitemap($locale);
				
		$pages = $sitemap->findBranch(($parentId) ? $parentId : null);	
				
		$data = new Emerald_Ajax_DataSource_Wrapper($pages);
		$wrappedData = $data->get($parentId, $parentId);
		
		$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, $wrappedData);

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message); 
	}
	
	/**
	 * Displays the reorder list where the user can drag&drop reorder pages
	 * 
	 * PERMISSIONS: root required
	 */ 
	public function reorderAction()
	{
		$validators = array(
			'order' => Array
			(
				'Int',
				'allowEmpty' => true,
				'presence' => 'optional'
			),
			'locale' => Array
			(
				'allowEmpty' => false,
				'presence' => 'required'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
			throw new Emerald_Exception('Forbidden', 403);
		}
		
		$allowLocales = array();
		foreach(Emerald_Model::get('Locale')->fetchAll() as $localeRaw)$allowLocales[] = $localeRaw->locale;
		if(!in_array( $filtered->locale, $allowLocales)) throw new Emerald_Exception('Not Found', 404);
		
		$parentId = $filtered->start;
		$sitemap = new Emerald_Sitemap($filtered->locale);
		$list = $this->_recursiveBuildList($sitemap);
		$list->addAttribute("id","sortList");
		$dom_sxe = dom_import_simplexml($list);
		$dom_sxe->ownerDocument->formatOutput = true;
		
		$this->view->locale = $filtered->locale;
		$this->view->pageList = $dom_sxe->ownerDocument->saveXML($dom_sxe,LIBXML_NOEMPTYTAG);
		$this->view->headScript()->appendFile("/lib/js/scriptaculous/src/scriptaculous.js");
		$this->view->headScript()->appendFile("/lib/js//scriptaculous/src/effects.js");
		$this->view->headScript()->appendFile("/lib/js//scriptaculous/src/dragdrop.js");
		
		$this->view->layout()->setLayout("admin_popup_outer");

	}
	/**
     * Builds an ordered list recursivelly (called from: reorderAction)
     * 
     * PERMISSIONS: None checked here since not callable
     */
	private function _recursiveBuildList($sitemap, $parentId = NULL)
	{
		static $homeId = NULL;
		if(!$homeId)
		{
			$homeId = $sitemap->getHomeId();
		}
		$xml = new SimpleXMLElement("<ol></ol>");
		$pages = $sitemap->findBranch($parentId);
		foreach($pages as $p)
		{
			$list = $xml->addChild("li", $p->title);
			$list->addAttribute("id", "node_".$p->id);
			if($homeId == $p->id)
			{
				$list->addAttribute("class", "homePage");
			}
			$parent = dom_import_simplexml($list);
			if($child = $this->_recursiveBuildList($sitemap, $p->id))
			{
				
				$dom_sxe = dom_import_simplexml($child);
				$sublist = $parent->ownerDocument->importNode($dom_sxe, true);
				
			}else{
				$sublist = $parent->ownerDocument->createElement("ol");
			}
			$parent->appendChild($sublist);
		}
		return $xml;
	}
	
	/**
	 * Writes the new order to the storage
	 * 
	 * PERMISSIONS: root required
	 */
	public function updateorderAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
			throw new Emerald_Exception('Forbidden', 403);
		}
		
		// zend filter does not seem to handle multi dimensional arrays..
		$orderList = $_POST['sortList'];
		if(!is_array($orderList)) throw new Emerald_Exception('Not Found', 404);
		
		$db = Zend_Registry::get('Emerald_Db');
		$db->beginTransaction();
		try {
			$this->_recursiveOrderBranch($orderList);
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, "");
		}catch(Exception $e){
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, "");
			$db->rollback();
		}
		$db->commit();
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message); 
	}
	/**
	 * Re-orders the pages recursivelly (called from updateOrderAction)
	 * 
	 * PERMISSIONS: none checked here since not callable from outside
	 */
	private function _recursiveOrderBranch($arrBranch, $parentId = NULL)
	{
		static $table = NULL;
		if(!$table)
		{
			$table = Emerald_Model::get('Page');
		}
		
		$order = 0;
		
		foreach($arrBranch as $order => $arrNode)
		{
			$nodeId = (int)$arrNode['id'];
			
			$page = $table->find($nodeId)->current();
			if(!$page) throw new Emerald_Exception('Not Found', 404);
			$page->parent_id = ($parentId) ? (int)$parentId : NULL;
			$page->order_id =(int)$order;
			$page->save();
			
			if(count($arrNode)>1)
			{
				unset($arrNode["id"]);
				$this->_recursiveOrderBranch($arrNode,$nodeId);
			}
		}
	}
	
	
	/**
	 * Edit an existing page (id)
	 * This action only prepares and displays the edit form
	 * 
	 * PERMISSIONS: write permission required for the edited page
	 */
	public function editpageAction()
	{
		$filters = Array();
		$validators = array(
			'id' => Array
			(
				'Int',
				'allowEmpty' => true,
				'presence' => 'required'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		// TODO: Lazy loading causes this hack. How we gonna get past it?
		$this->view->groups = Emerald_Model::get("Group")->fetchAll();
		foreach($this->view->groups as $group) {
			// We just *need* 'em groups to use 'em. Go hacks!
		}
		
		
		
		$id = $filtered->id;
		$this->view->layout()->setLayout("admin_popup_outer");
		
		$dao = Emerald_Model::get('Page');
		$page = $dao->find($id)->current();
		
		if(!$page) 
		{
			throw new Emerald_Exception("Not found (#{$id})!");
		}
		
		if(!$this->_isUserAllowed($page, 'write'))
		{
			throw new Emerald_Exception("Forbidden", 403);
		}
		
		$this->view->parentId = $page->parent_id;
		$this->view->id = $page->id;
		$s_dao = Emerald_Model::get("Shard");
		$this->view->shards = $s_dao->findAllowed();
		$tpl = new Emerald_Templating();
		$this->view->layouts = $tpl->getLayouts();
				
		 
		$this->view->permissions = Emerald_Permission::getAll();
		$this->view->page = $page;
		$this->view->acl = Zend_Registry::get('Emerald_Acl');
		
		$this->view->headScript()->appendFile("/lib/js/admin/sitemap/editpage.js");
		$this->view->headScript()->appendFile("/lib/js/form.js");
		$this->render("edit-properties");
	}
	
	/**
	 * Create a new page under (id)
	 * This action only prepares and displays the edit form
	 * 
	 * PERMISSIONS: If parent id is not null, the user needs to have a write 
	 * permission on the parent page (since we're creating a subpage). If no parent id is
	 * provided (is null) we're going to create a top level page, which requires root permissions.
	 */
	public function createpageAction()
	{
		$validators = array(
			'id' => Array
			(
				'Int',
				'allowEmpty' => true,
				'presence' => 'required'
			),
			'locale' => Array
			(
				'allowEmpty' => false,
				'presence' => 'required'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		$parentId = $filtered->id;
		$parentPage = null;
		
		// TODO: Lazy loading causes this hack. How we gonna get past it?
		$this->view->groups = Emerald_Model::get("Group")->fetchAll();
		foreach($this->view->groups as $group) {
			// We just *need* 'em groups to use 'em. Go hacks!
		}
		
		
		// if we have parentid create the page under that, else assume user wants 
		// to create a new root level page 
		if($parentId) 
		{
			$dao = Emerald_Model::get('Page');
			// check if can write to parent
			$parentPage = $dao->find($parentId)->current();
			if(!$parentPage) 
			{
				throw new Emerald_Exception("Not found", 404);
			}
			if(!$this->_isUserAllowed($parentPage, 'write'))
			{
				throw new Emerald_Exception("Forbidden", 403);
			}
		}
		// creating at root level requires root
		else if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$this->view->layout()->setLayout("admin_popup_outer");
		$this->view->parentId = $parentId;
		$this->view->localeName = $filtered->locale;
		
		$s_dao = Emerald_Model::get("Shard");
		$this->view->shards = $s_dao->findAllowed();
		$tpl = new Emerald_Templating();
		$this->view->layouts = $tpl->getLayouts();
		
		
		// TODO: Lazy loading causes this hack. How we gonna get past it?
		$this->view->groups = Emerald_Model::get("Group")->fetchAll();
		foreach($this->view->groups as $group) {
			// We just *need* 'em groups to use 'em. Go hacks!
		}
		
		
		 
		$this->view->permissions = Emerald_Permission::getAll();

		
		$this->view->page = $parentPage;	 
			
		
			
		$this->view->acl = Zend_Registry::get('Emerald_Acl');
		
		
		$this->view->headScript()->appendFile("/lib/js/form.js");
		$this->view->headScript()->appendFile("/lib/js/admin/sitemap/editpage.js");
		$this->render("edit-properties");
	}
	
	/**
	 * Saves page changes / Creates a new page
	 * 
	 * PERMISSIONS: If parent id is not null, the user needs to have a write 
	 * permission on the parent page (since we're creating a subpage). If no parent id is
	 * provided (is null) we're going to create a top level page, which requires root permissions.
	 */
	public function savepageAction()
	{
		$validators = array(
			'id' => Array
			(
				'Int',
				'allowEmpty' => true
			),
			'locale' => Array
			(
				'allowEmpty' => false,
				'presence' => 'required'
			),
			'parent_id' => Array
			(
				'Int', 
				'allowEmpty' => true, 
				'presence' => 'required'
			),
			'title' => Array
			(
				Array('StringLength',1,255),
				'presence' => 'required',
				'allowEmpty' => false
			),
			'shard_id' => Array
			(
				'Int', 
				'presence' => 'required'
			),
			'layout' => Array
			(
				Array('StringLength',1,255),
				'presence' => 'required'
			),
			'visibility' => Array(new Zend_Validate_InArray(array(0,1)), 'presence' => 'required'),

			'page_count' => Array
			(
				'Int'
			),
			'permission' => Array
			(
				
			)
		);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$filtered->process();
		}
		catch(Exception $e)
		{
			$fields = array_keys(array_merge($filtered->getMissing(),$filtered->getInvalid()));
			
			$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::ERROR, array('fields'=>$fields)));
			return;
		}
		
		
		
		
		$allowLocales = array();
		foreach(Emerald_Model::get('Locale')->fetchAll() as $localeRaw) {
			$allowLocales[] = $localeRaw->locale;			
		}
		if(!in_array( $filtered->locale, $allowLocales))
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		$locale = $filtered->locale; // user get edit locale HERE
		
		$dao = Emerald_Model::get('Page');
		$parentId = $filtered->parent_id;
		
		
		// if not creating at root level 
		if($parentId) 
		{
			// check if can write to parent
			$parentPage = $dao->find($parentId)->current();
			if(!$parentPage) 
			{
				throw new Emerald_Exception("Not found", 404);
			}
			if(!$this->_isUserAllowed($parentPage, 'write'))
			{
				throw new Emerald_Exception("Forbidden", 403);
			}
			// parent found, force same language
			$locale = $parentPage->locale;
		}
		// creating at root level requires root
		else if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$newPages = Array();
		
		// check if creating or updating (depends if the id is present)
		if($id = $filtered->getEscaped("id"))
		{
			$obj = $dao->find($id)->current();
			if(!$obj) throw new Emerald_Exception("Not found", 404);
			$obj->id = $id;
			
			$newPages[] = $obj;
		}
		else
		{
			while($filtered->page_count--) $newPages[] = $dao->createRow();
		}
		
		$db = Zend_Registry::get('Emerald_Db');
		try
		{
			$db->beginTransaction();
			
			
			foreach($newPages as $cnt => $page)
			{
				$page->parent_id = $filtered->parent_id ? $filtered->parent_id : null;
				$page->title = $filtered->title;
				if($cnt > 0) $page->title .= " ({$cnt})";
				$page->shard_id = $filtered->shard_id;
				$page->layout = $filtered->layout;
				
				// keep the old order-id if existing page, else get max from dao
				$page->order_id = $page->order_id ? $page->order_id : $dao->getNextOrderId($page->parent_id, $locale);
				$page->status = 1;
				$page->locale = $locale;
				$page->visibility = $filtered->visibility;
				
				
				if(!$page->parent_id) $page->parent_id = NULL;
				$page->save();

				$permissionTbl = Emerald_Model::get('Permission_PageGroup');
				$permissionTbl->delete('page_id = ' . $page->id);
								
				foreach($filtered->permission as $groupId => $permissionArr)
				{
					if($grp = Emerald_Model::get('Group')->find($groupId)->current())
					{
						$permission = Emerald_Model::get('Permission_PageGroup')->find($page->id,$groupId)->current();
						if(!$permission)
						{
							$permission = Emerald_Model::get('Permission_PageGroup')->createRow();
						}
						$permission->page_id = $page->id;
						$permission->ugroup_id = $grp->id;
						$permission->permission = array_sum($permissionArr);
						$permission->save();
					}
				}
				
			}
			$db->commit();
			$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, $this->view->translate('common/saved')));
		}
		catch(Exception $e)
		{
			$db->rollback();
			$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::ERROR, $this->view->translate('common/failed')));
		}
		
	}
	/**
	 * Returns the page as json
	 * 
	 * TODO: should the permission be something else than write - currently set to write because
	 * the read permission does not necessary mean that the user should be able to see all page properties.
	 * 
	 * PERMISSIONS: requires write permission for the requested page
	 */
	public function getpagedataAction()
	{
		$validators = array(
			'id' => Array
			(
				'Int',
				'allowEmpty' => false,
				'presence' => 'required'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		$id = $filtered->id;
		
		$dao = Emerald_Model::get('Page');
		$page = $dao->find($id)->current();
		
		if(!$page) throw new Emerald_Exception("Not found", 404);
		if(!$this->_isUserAllowed($page, 'write'))
		{
			throw new Emerald_Exception("Forbidden", 403);
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, $page->toArray()));
	}
	/**
	 * Physically removes page(s) from database (this should be changed)
	 * 
	 * PERMISSIONS: requires write permissions for all removed pages 
	 * (but not for the subpages - they are deleted even if the user 
	 * does not have the write permission for them - which is kinda fucked up :()
	 * 
	 * TODO: Fix dem permissions for them subpages
	 */
	public function deletepageAction()
	{
		$validators = array
		(
			'ids' => Array // this must be an array
			(
				'Int',
				'allowEmpty' => false,
				'presence' => 'required'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		$ids = $filtered->ids;
		$dao = Emerald_Model::get('Page');
		$db = Zend_Registry::get('Emerald_Db');
		try
		{
			$db->beginTransaction();
			$ordered = Array();
			foreach($ids as $id)
			{
				$id = (int) $id;
				$page = $dao->find($id)->current();
				if(!$page) throw new Emerald_Exception("Not found");
				
				$ordered[count(explode(";", $page->path))][] = $page;
			}
			
			// deleting must proceed from leaf to trunk to avoid missing nodes 
			// (ok, this might need some explaining) since the user might have selected
			// both a parent page and its subpage for deletion, we "must" start the delete operation
			// from the subpage because if we deleted the parent page first, the subpage would
			// be implicitelly deleted and we'd get a "not found" error when we tried to delete id..
			
			
			krsort($ordered);
			
			foreach($ordered as $level => $pages)
			{
				foreach($pages as $page)
				{
					if(!$this->_isUserAllowed($page, 'write'))
					{
						throw new Emerald_Exception("Forbidden", 404);
					}
					$page->delete();
				}
			}
			$db->commit();
			$message =  new Emerald_Json_Message(Emerald_Json_Message::SUCCESS,Array());
		}
		catch(Exception $e)
		{
			$db->rollback();
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR,Array());
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
	}
	
	/**
	 * Sets the given page as the new starting page for the current locale
	 * 
	 * PERMISSIONS: root required
	 */
	public function sethomeAction()
	{
		$validators = array
		(
			'id' => Array // this must be an array
			(
				'Int',
				'allowEmpty' => false,
				'presence' => 'required'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		
		if($this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
			$id = $filtered->id;
			if($page = Emerald_Model::get("Page")->find($id)->current())
			{
				if($locale = Emerald_Model::get("Locale")->find($page->locale)->current())
				{
					$locale->page_start = $page->id;
					$locale->save();
					
					$this->getResponse()->appendBody(new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, "common/success"));
					return;
				}
			}
		}
		$this->getResponse()->appendBody(new Emerald_Json_Message(Emerald_Json_Message::ERROR, "common/forbidden"));
	}
	/**
	 * Quick edit in place action for changin page titles
	 * 
	 * PERMISSIONS: requires write for the given page
	 */
	public function changetitleAction()
	{
	
		$filters = Array();
		$validators = array(
			
			'eip' => Array // array of id=>title pairs
			(
				Array('StringLength',1,255),
				'allowEmpty' => false,
				'presence' => 'required',
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$filtered->process();
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, Array());
			$dao = Emerald_Model::get('Page');
			$db = Zend_Registry::get('Emerald_Db');
			try
			{
				$db->beginTransaction();
				foreach($filtered->eip as $id => $title)
				{
					$id = (int)$id;
					$page = $dao->find($id)->current();
					if(!$page) throw new Exception("pagenotfound");
					$page->title = $title;
					$page->save();
				}
				$db->commit();
				
			}catch(Exception $e){
				$db->rollback();
				throw $e;
			}
			
		}
		catch(Exception $e)
		{
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR,Array());
		}
		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
		
		
	}
	/**
	 * Renders json for tinymce internal link list
	 * 
	 * PERMISSION: only readable pages are included in the list (same as in the sitemap)
	 */ 
	public function tinymcelinklistAction()
	{
		$localeTbl = Emerald_Model::get('Locale');
		$locales = $localeTbl->fetchAll();
		$data = Array();
		foreach($locales as $locale)
		{
			$data[] = "['------- ".strtoupper($locale->locale)." -------','/$locale->locale']";
			$sitemap = new Emerald_Sitemap($locale->locale);
			$data = array_merge($data, $this->_renderTinyMCEList(null, $sitemap));
		}
		
		$message = 'var tinyMCELinkList = new Array('.implode(",",$data).');';
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
	}
	/**
     * This handles the actual rendering for the tinymcelinkistaction
     */
	private function _renderTinyMCEList($branchId, $sitemap)
	{
		$this->_level++;
		$output = Array();
		$branch = $sitemap->findBranch($branchId);
		foreach($branch as $node) {
			
			$title = str_repeat(" - ",$this->_level).$node->title;
			$target = "/page/view/id/{$node->id}";//$node->iisiurl;
			$output[] = "['$title','$target']";
			$output = array_merge($output, $this->_renderTinyMCEList($node->id, $sitemap));
			
			
		}
		$this->_level--;
		return $output;
	}
	
}
