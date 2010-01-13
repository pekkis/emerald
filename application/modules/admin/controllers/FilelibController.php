<?php
class Admin_FilelibController extends Emerald_Controller_Action 
{
		
	public $ajaxable = array(
		'create-folder' => array('json'),
		'delete-folder' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
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

			$className = $fl->getFolderItemClass();
			
			$folderItem = new $className($folderForm->getValues());
			
			$fl->createFolder($folderItem);

			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Great success');					
			
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Epic fail');
			$msg->errors = $folderForm->getMessages();
		}
		$this->view->message = $msg;
				
	}
	
	
	public function submitAction()
	{
		$filelib = Zend_Registry::get('Emerald_Filelib');
		$form = new Admin_Form_FileUpload();

		if($form->isValid($this->getRequest()->getPost())) {
			$folder = $filelib->findFolder($form->folder_id->getValue());
			$form->file->receive();
			$file = $filelib->upload($form->file->getFileName(), $folder);

			$this->view->success = true;
			$this->view->folder_id = $form->folder_id->getValue();
		} else {
			$this->view->success = false;
			$this->view->folder_id = $this->_getParam('folder_id');
		}
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
				
				$this->getHelper('redirector')->gotoRouteAndExit(array('id' => $folder->id));
				
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
