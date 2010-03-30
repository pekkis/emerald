<?php
class EmCore_InstallController extends Emerald_Controller_Action
{
	
	
	public function indexAction()
	{
		$installed = $this->getCustomer()->getOption('installed');
		if($installed) {
			throw new Emerald_Exception("Already installed", 500);
		}
		
		$form = new EmCore_Form_Install();
		$this->view->form = $form;
	}
	
	
	public function postAction()
	{
		$installed = $this->getCustomer()->getOption('installed');
		if($installed) {
			throw new Emerald_Exception("Already installed", 500);
		}
		
		
		$form = new EmCore_Form_Install();
		
		try {
			if(!$form->isValid($this->getRequest()->getPost())) {
				
				throw new Emerald_Exception('Invalid form');
				
			} else {
				
				
				$groupTbl = new EmCore_Model_DbTable_Ugroup();
				$userTbl = new EmCore_Model_DbTable_User();
				$ugTbl = new EmCore_Model_DbTable_UserGroup();
				$folderTbl = new Emerald_Filelib_Backend_Db_Table_Folder();
				
				$db = $groupTbl->getAdapter();
				
				
				try {

					$db->beginTransaction();
					$groupTbl->insert(array('name' => 'Anonymous'));
					
					$groupId = $groupTbl->insert(array('name' => 'Root'));
					$userId = $userTbl->insert(array('email' => $form->email->getValue(), 'passwd' => md5($form->password->getValue()), 'status' => 1));
					
					$ugTbl->insert(array('user_id' => $userId, 'ugroup_id' => $groupId));

					$folderTbl->insert(array('name' => 'root', 'parent_id' => null));
					
					$this->getCustomer()->setOption('installed', '1');

					$db->commit();
					
					$this->getHelper('redirector')->gotoUrlAndExit('/');
					
				} catch(Exception $e) {
					
					echo $e;
					die();
					
					$db->rollBack();
					throw $e;
				}
				
				
				
			}
		} catch(Exception $e) {
			$this->view->form = $form;
			$this->getHelper('viewRenderer')->setScriptAction('index');
		}
		
		
	}
	
	
}