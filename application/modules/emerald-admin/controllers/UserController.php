<?php
class EmeraldAdmin_UserController extends Emerald_Controller_AdminAction
{
	
	public function indexAction()
	{
		$this->_forward("userList");	
	}
	/**
	 * Lists all users - only renders the template, users are fetched using js and loadusersAction
	 * 
	 * PERMISSIONS: requires root
	 */
	public function userlistAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		Emerald_Js::addjQueryUi($this->view);
		$this->view->headScript()->appendFile("/lib/js/emerald-admin/datasource.js");
		$this->view->headScript()->appendFile("/lib/js/emerald-admin/user/user_list.js");
	}
	/**
     * Returns a list of all users in json
     * 
     * PERMISSIONS: requires root
     */
	public function loadusersAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'start' => array('Int', 'presence' => 'required'),
			'end' => array('Int', "allowEmpty" => true,"allowNull" => true)
		);
		try {
			
			$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$filtered->process();

			$start = $filtered->start;
			$end = $filtered->end;
			$sort = $filtered->sort;
			
			$data = Array();
			$totalCount = 0;
			$users = Emerald_Model::get('User')->findIndexed("lastname", $start, $end, $totalCount);
			
			$usersFiltered = Array(); // do not show root and anon
			foreach($users as $usr) if($usr->id > 1) $usersFiltered[] = $usr;
			
			$data = new Emerald_Ajax_DataSource_Wrapper($usersFiltered);
			$wrappedData = $data->get($start, $end, $totalCount);
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, $wrappedData);
			
		} catch(Exception $e) 
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
	}
	/**
	 * Displays a form for viewing a single user
	 * 
	 * PERMISSIONS: requires root
	 */
	public function viewuserAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		$filters = array();
		$validators = array(
			'id' => array('Int', new Zend_Validate_GreaterThan(1), 'presence' => 'required'),
		);
		
		try {
			
			$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$filtered->process();

			$id = $filtered->id;
			$this->view->user = Emerald_Model::get('User')->find($id)->current();
			$this->view->groups = Emerald_Model::get('Group')->fetchAll();
			
			$adminLocales = array();
			$locale = new Zend_Locale();
			foreach(Emerald_Server::getInstance()->getAdminLocales() as $pocale) {
				$adminLocales[$pocale] = $locale->getTranslation($pocale, 'language', $pocale);
			}
			$this->view->availableLocales = $adminLocales;
			
			$this->view->headScript()->appendFile("/lib/js/form.js");
			$this->view->headScript()->appendFile("/lib/js/emerald-admin/user/user_edit.js");
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Not Found', 404);
		}
	}
	/**
	 * Displays a form for creating a new user
	 * 
	 * PERMISSIONS: requires root
	 */
	public function viewnewuserAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		try {
			$this->view->user = Emerald_Model::get('User')->createRow();
			$this->view->groups = Emerald_Model::get('Group')->fetchAll();
			
			$adminLocales = array();
			$locale = new Zend_Locale();
			foreach(Emerald_Server::getInstance()->getAdminLocales() as $pocale) {
				$adminLocales[$pocale] = $locale->getTranslation($pocale, 'language', $pocale);
			}
			$this->view->availableLocales = $adminLocales;
			$this->view->headScript()->appendFile("/lib/js/form.js");
			$this->view->headScript()->appendFile("/lib/js/emerald-admin/user/user_edit.js");
			$this->render("view-user");
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Not Found', 404);
		}
	}
	/**
	 * Saves user data to storage
	 * 
	 * PERMISSIONS: requires root
	 */
	public function saveuserAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$filters = array();
		
		$validators = array(
			'id' => Array
			(
				'Int',
				new Zend_Validate_GreaterThan(1),
				'allowEmpty' => true
			),
			'email'=> Array(
				Array('StringLength',1,255),
				'EmailAddress'
			),
			'firstname' => Array
			(
				Array('StringLength',1,255),
				'allowEmpty' => false
			),
			'lastname' => Array
			(
				Array('StringLength',1,255),
				'allowEmpty' => false
			),
			'password' => Array
			(
				new Emerald_Validate_PasswordEquality(),
				'fields'=>Array('password','password_confirm'),
				'presence' => 'optional',
				'allowEmpty' => true
			),
			'groups' => Array
			(
				'presence' => 'optional',
				'allowEmpty' => true
			),
			'options'=>Array
			(
				//options whitelist filtered in setUserOptions
				'presence' => 'optional',
				'allowEmpty' => true
			),
		);
		
		$db = Zend_Registry::get('Emerald_Db');
		$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
		$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
		try 
		{
			$db->beginTransaction();
			$filtered->process();
			
			
			if(!$filtered->id) // assume new user
			{
				// new user needs to have the passwd fields filled
				if(!$filtered->password)
				{
					$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::ERROR, array('fields'=>Array('password', 'password_confirm'))));
					return;
				}
				
				$user = Emerald_Model::get('User')->createRow();
			}
			else
			{
				$user = Emerald_Model::get('User')->find($filtered->id)->current();
			}

			$user->firstname = $filtered->firstname;
			$user->lastname = $filtered->lastname;
			$user->email = $filtered->email;
			if($filtered->password)
			{
				$user->passwd= md5($filtered->password);
			}		
			$user->status = 1;
			
			$this->_setUserOptions($user, $filtered->options);
			
			$user->save();
			
			if(!is_array($filtered->groups)) $filtered->groups = Array();
			
			$ugTable = Emerald_Model::get('UserGroup');
			$ugTable->setUserGroups($user->id, $filtered->groups);
			
			
			$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'common/success'));
			$db->commit();
		}
		catch(Zend_Filter_Exception $e)
		{	
			$db->rollback();
			$fields = array_keys(array_merge($filtered->getMissing(),$filtered->getInvalid()));
			$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::ERROR, array('fields'=>$fields)));
		}
		catch(Exception $e) 
		{
			$db->rollback();
			throw new Emerald_Exception('Not Found', 404);
		}
		return;
	}
	
	private function _setUserOptions($user, Array $options)
	{

		
		$validators = array(
			'locale' => Array
			(
				array('InArray',Emerald_Server::getInstance()->getAdminLocales()),
				'allowEmpty' => false
			)
		);
		
		$filtered = new Zend_Filter_Input(array(), $validators, $options);
		$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
		try 
		{
			$filtered->process();
			foreach($filtered->getEscaped() as $k => $v)
			{
				$user->setOption($k,$v);
			}
		}catch(Exception $e){}
		
			
	}
	/**
	 * Removes an user
	 * 
	 * PERMISSIONS: requires root
	 */
	public function deleteuserAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'ids' => Array
			(
				'Int',
				new Zend_Validate_GreaterThan(2),
				'presence' => 'required',
				'allowEmpty' => false
			)
		);
		$db = Zend_Registry::get('Emerald_Db');
		$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
		try 
		{
			$db->beginTransaction();
			$filtered->process();
			foreach($filtered->ids as $id)
			{
				$user = Emerald_Model::get('User')->find($id)->current();
				if(!$user) throw new Exception("user not found - bailout");
				$user->delete();
			}
			
			$db->commit();
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, "deleted");
		}
		catch(Exception $e)
		{	
			$db->rollback();
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, "error");
		}
		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
	}
	//
	
	
	
	
	
	/**
	 * Shows group list template
	 * 
	 * PERMISSIONS: requires root
	 
	public function grouplistAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		$this->view->headScript()->appendFile("/lib/js/emerald-admin/datasource.js");
		$this->view->headScript()->appendFile("/lib/js/emerald-admin/user/group_list.js");
	}
	*/
	/**
	 * Returns all the groups as json data
	 * 
	 * PERMISSIONS: requires root
	 */
	public function loadgroupsAction()
	{
	
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		$filters = array();
		$validators = array(
			'start' => array('Int', 'presence' => 'required'),
			'end' => array('Int', "allowEmpty" => true,"allowNull" => true)
		);
		try {
			
			$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$filtered->process();

			$start = $filtered->start;
			$end = $filtered->end;
			$sort = $filtered->sort;
			
			$data = Array();
			$totalCount = 0;
			$groups = Emerald_Model::get('Group')->findIndexed("name", $start, $end, $totalCount);
			$groupsFiltered = Array(); // do not show root and anon
			foreach($groups as $grp) if($grp->id > 2) $groupsFiltered[] = $grp;
			$data = new Emerald_Ajax_DataSource_Wrapper($groupsFiltered);
			
			$wrappedData = $data->get($start, $end, $totalCount);
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, $wrappedData);
			
		} catch(Exception $e) 
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
	}
	
	/**
     * Views the edit page for editing/viewing a single group
     * 
     * PERMISSIONS: requires root
     */
	public function viewgroupAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'id' => array('Int', new Zend_Validate_GreaterThan(2), 'presence' => 'required'),
		);
		
		try {
			
			$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$filtered->process();

			$id = $filtered->id;
			$this->view->group = Emerald_Model::get('Group')->find($id)->current();
			$this->view->headScript()->appendFile("/lib/js/form.js");
			$this->view->headScript()->appendFile("/lib/js/emerald-admin/user/group_edit.js");
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Not Found', 404);
		}
	}
	
	/**
     * Views the edit page for creating a new group
     * 
     * PERMISSIONS: requires root
     */
	public function viewnewgroupAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		try {
			$this->view->group = Emerald_Model::get('Group')->createRow();
			$this->view->headScript()->appendFile("/lib/js/form.js");
			$this->view->headScript()->appendFile("/lib/js/emerald-admin/user/group_edit.js");
			$this->render("view-group");
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Not Found', 404);
		}
	}
	/**
	 * Save the group data (either create new or edit existing)
	 * 
	 * PERMISSIONS: root required
	 */
	public function savegroupAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$filters = array();
		
		$validators = array(
			'id' => Array
			(
				'Int',
				new Zend_Validate_GreaterThan(2),
				'allowEmpty' => true
			),
			'name'=> Array(
				Array('StringLength',1,255),
				'allowEmpty' => false,
				'presence' => 'required'
			)
		);
		
		$db = Zend_Registry::get('Emerald_Db');
		$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
		$filtered->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
		try 
		{
			$db->beginTransaction();
			$filtered->process();
			
			
			if(!$filtered->id) // assume new user
			{
				$group = Emerald_Model::get('Group')->createRow();
			}
			else
			{
				$group = Emerald_Model::get('Group')->find($filtered->id)->current();
			}

			$group->name = $filtered->name;
			$group->save();
			
			$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'common/success'));
			$db->commit();
		}
		catch(Zend_Filter_Exception $e)
		{	
			$db->rollback();
			$fields = array_keys(array_merge($filtered->getMissing(),$filtered->getInvalid()));
			$this->getResponse()->setHeader('X-JSON', new Emerald_Json_Message(Emerald_Json_Message::ERROR, array('fields'=>$fields)));
		}
		catch(Exception $e) 
		{
			$db->rollback();
			throw new Emerald_Exception('Not Found', 404);
		}
		return;
	}
	/**
	 * Removes a group
	 * 
	 * PERMISSIONS: requires root
	 */
	public function deletegroupAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		$filters = array();
		$validators = array(
			'ids' => Array
			(
				'Int',
				new Zend_Validate_GreaterThan(2),
				'presence' => 'required',
				'allowEmpty' => false
			)
		);
		$db = Zend_Registry::get('Emerald_Db');
		$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
		try 
		{
			$db->beginTransaction();
			$filtered->process();
			foreach($filtered->ids as $id)
			{
				$group = Emerald_Model::get('Group')->find($id)->current();
				if(!$group) throw new Exception("group not found - bailout");
				$group->delete();
			}
			
			$db->commit();
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, "deleted");
		}
		catch(Exception $e)
		{	
			$db->rollback();
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, "error");
		}
		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
	}
}