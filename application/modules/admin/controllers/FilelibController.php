<?php
class Admin_FilelibController extends Emerald_Controller_AdminAction 
{
		
	public function folderPropertiesAction()
	{
		$filters = array();
		$validators = array(
			'id' => array('Alnum', 'presence' => 'required'),
		);
				
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();

			// TODO: Lazy loading causes this hack. How we gonna get past it?
			$this->view->groups = Emerald_Model::get("Group")->fetchAll();
			foreach($this->view->groups as $group) {
				// We just *need* 'em groups to use 'em. Go hacks!
			}
			
			$folder = Emerald_Model::Get('Filelib_Folder')->find($input->id)->current();

			if(!$folder) {
				throw new Emerald_Exception('Folder not found', 404);
			}

			$folder->assertWritable();

			$this->view->acl = Zend_Registry::get('Emerald_Acl');
			$this->view->folder = $folder;
			 
			$this->view->permissions = Emerald_Permission::getAll();
				
			
			$this->view->headScript()->appendFile('/lib/js/admin/filelib/folder-properties.js');
			$this->view->layout()->setLayout('admin_popup_outer');
			
			
		} catch(Emerald_Exception $e) {
			throw $e;
		} catch(Zend_Filter_Exception $e) {
			throw new Emerald_Exception('Folder not found', 404);
		} catch(Exception $e) {
			
		}
		
		
	}
	
	public function folderSaveAction()
	{
		$filters = array();
		$validators = array(
			'id' => array('Alnum', 'presence' => 'required'),
			'permission' => array(),
		);
				
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
			
			$folder = Emerald_Model::Get('Filelib_Folder')->find($input->id)->current();

			if(!$folder) {
				throw new Emerald_Exception('Folder not found', 404);
			}

			$folder->assertWritable();
			
			$acl = Zend_Registry::get('Emerald_Acl');
			
			$permissionTbl = Emerald_Model::get('Permission_Filelib_FolderGroup');
			
			$where = $permissionTbl->getAdapter()->quoteInto('folder_id = ?', $folder->id);
			$permissionTbl->delete($where);
						
			foreach($input->permission as $groupId => $permissionArr)
			{
				if($grp = Emerald_Model::get('Group')->find($groupId)->current())
				{
					$permission = Emerald_Model::get('Permission_Filelib_FolderGroup')->find($folder->id,$groupId)->current();
					if(!$permission)
					{
						$permission = Emerald_Model::get('Permission_Filelib_FolderGroup')->createRow();
					}
					$permission->folder_id = $folder->id;
					$permission->ugroup_id = $grp->id;
					$permission->permission = array_sum($permissionArr);
					$permission->save();
				}
			}
			
			
			$acl->remove($folder);
			$folder->init();
			
			$group = Emerald_Model::get('Group')->find(Emerald_Group::GROUP_ANONYMOUS)->current();
			if($acl->isAllowed($group, $folder, 'read')) {
				foreach($folder->getFiles() as $file) {
					$file->createSymlink();
				}
			} else {
				foreach($folder->getFiles() as $file) {
					$file->deleteSymlink();
				}
			}
						
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'l:common/save_ok');
			
		} catch(Exception $e) {
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'l:common/save_failed');
			$message->exception = $e->getMessage();
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
		
		
	}
	
	
	
	public function monitoruploadAction()
	{
		$filters = array();
		$validators = array(
			'id' => array('Alnum', 'presence' => 'required'),
		);
				
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
			
			
			$info = uploadprogress_get_info($input->id);

			$info = Zend_Json::encode($info);
			
			$this->_helper->_layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
			$this->getResponse()->appendBody($info);
						
			
		} catch(Exception $e) {
			die('xäxx');
		}
	}
	
	
	public function uploadfileAction()
	{
		$filters = array();
		$validators = array(
			'folder_id' => array('Int', 'presence' => 'required'),
		);
		
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
		
			
			$folderTbl = Emerald_Model::get('Filelib_Folder');
			$folder = $folderTbl->fetchRow(array('id = ?' => $input->folder_id));
						
			$file = $_FILES['theFile'];
									
			$filelib = Emerald_Filelib::getInstance();
			
			if(is_uploaded_file($file['tmp_name']) && !$file['error']) {
				
				$uploadable = new Emerald_Filelib_FileUpload($file['tmp_name']);
																			
				$uploadable->setOverrideFilename($file['name']);
				$uploaded = $filelib->upload($uploadable, $folder);
			
			}
						
		} catch(Exception $e) {
			
			die($e->getMessage());
			
			// $this->_forward('uploadFileFailed');
		}
		
	}
	
	
	
	public function createFolderAction()
	{
		$folderForm = new Admin_Form_CreateFolder();

		$fl = Zend_Registry::get('Emerald_Filelib');
		
		if($folderForm->isValid($this->getRequest()->getParams())) {

			$folderItem = new Emerald_Filelib_FolderItem($folderForm->getValues());
			
			$fl->createFolder($folderItem);
			
			Zend_Debug::dump($folderForm->getValues());
			
			die('a ok');
			
						
			
		}
		
	
		die('fail');
				
	}
	
	
	
	public function selectAction()
	{
		$filters = array();
		$validators = array(
			'id' => array('Int', 'default_value' => null)
		);
		
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();		

			
			$tree = $this->_buildTree();
			
			$fileTbl = Emerald_Model::get('Filelib_File');

			$expr = ($input->id) ? $input->id : new Zend_Db_Expr('null');
		
			$files = $fileTbl->fetchAll(
				array('folder_id = ?' => $expr)
			);

			$this->view->active = $input->id;
			$this->view->tree = $tree;
			$this->view->files = $files;
		
			$this->view->headScript()->appendFile('/lib/js/tinymce/jscripts/tiny_mce/tiny_mce_popup.js');			
			$this->view->headScript()->appendFile('/lib/js/admin/filelib/select.js');
			
			
			$this->view->layout()->setLayout("admin_popup_outer");
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e, 500);
		}
		
		
	}
	
	
	    public function submitAction()
        {
                $filelib = Zend_Registry::get('Emerald_Filelib');
                $form = new Admin_Form_FileUpload();

                if($form->isValid($_POST)) {

                        $folder = $filelib->findFolder($form->folder_id->getValue());

                        $form->file->receive();

                        $file = $filelib->upload($form->file->getFileName(), $folder);

                        Zend_Debug::dump($file);


                }



                die('mööööh');



        }
	
	
	
	
	public function indexAction()
	{
	
		$filters = array();
		$validators = array(
			'id' => array('Int', 'default_value' => null)
		);
		
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();		

			$fl = Zend_Registry::get('Emerald_Filelib');
			$this->view->fl = $fl;
			
			$folder = $fl->findRootFolder();
			$iter = new Emerald_Filelib_FolderItemIterator($folder);
			
			$iter = new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::SELF_FIRST);
			
			$this->view->iter = $iter;
			
			
			
			
			
			if($input->id) {
				$activeFolder = $fl->findFolder($input->id);
				if(!$activeFolder) {
					throw new Emerald_Exception('Folder not found.', 404);
				}
				
				$this->view->folder = $activeFolder;
				$files = $activeFolder->findFiles();
				$this->view->files = $files;

				$folderForm = new Admin_Form_CreateFolder();
				$folderForm->parent_id->setValue($activeFolder->id);
				$this->view->folderForm = $folderForm;
				
				$form = new Admin_Form_FileUpload();
				$form->folder_id->setValue($activeFolder->id);
				$this->view->form = $form;
				
			} else {
				
			}
			
			
			
			// $folder = new Filelib_Model_FolderIterator($fl, null);
			
			
			// $tree = new RecursiveIteratorIterator($folder, RecursiveIteratorIterator::SELF_FIRST);
			

			
			/*
			$expr = ($input->id) ? $input->id : new Zend_Db_Expr('null');
			
			$files = array();
			if($input->id) {
				$fileTbl = $fl->getFileTable();
				$files = $fileTbl->fetchAll(
					array('folder_id = ?' => $expr)
				);
				
				
			}
			*/
			
				
			// $tree = $this->_buildTree();

			/*
			$token = md5(uniqid(rand(), true));
			
			$this->view->token = $token;
			
			$this->view->active = $input->id;
						
			
			$this->view->tree = $tree;
			$this->view->files = $files;

			$this->view->headScript()->appendFile('/lib/js/admin/filelib/index.js');
			$this->view->headScript()->appendFile('/lib/js/scriptaculous/src/scriptaculous.js');
			$this->view->headScript()->appendFile('/lib/js/lightbox2/js/lightbox.js');
			$this->view->headScript()->appendFile('/lib/js/scriptaculous/src/effects.js');
						
			$this->view->headLink()->appendStylesheet('/lib/js/lightbox2/css/lightbox.css');
			
			$this->view->headScript()->appendFile('/lib/js/jquery/jquery.hoveraction.js');
			
			$this->view->headLink()->appendStylesheet('/lib/css/admin/filelib/index.css');
			
			*/
			
		} catch(Emerald_Exception $e) {
			throw $e;
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e, 500);
		}
		
		
		
	}
	
	
	public function deleteAction()
	{
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'default_value' => null)
		);
		
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();		
			
			
			$file = Emerald_Model::get('Filelib_File')->find($input->id)->current();
			
			$folder = $file->getFolder();
			
			Emerald_Filelib::getInstance()->delete($file);
			
		
		} catch(Exception $e) {
			
			
			
			
			
		}
		
		$this->_redirect("/admin/filelib/index/id/{$folder->id}");
				
		
	}
	
	
	
	
	private function _buildTree()
	{
		$folderTbl = Emerald_Model::get('Filelib_Folder');
		$foldersRaw = $folderTbl->fetchAll(null, array('parent_id', 'name'));
		
		$folders = array();
		foreach($foldersRaw as $folder) {
			$folders[$folder->parent_id][] = $folder;
		}
				
		return $folders;
		
	}
	
	
}
