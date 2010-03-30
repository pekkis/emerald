<?php
class EmAdmin_FilelibController extends Emerald_Controller_Action 
{
		
	public $ajaxable = array(
		'create-folder' => array('json'),
		'delete-folder' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
		
	public function createFolderAction()
	{
		$folderForm = new EmAdmin_Form_CreateFolder();

		$fl = Zend_Registry::get('Emerald_Filelib');
		
		if($folderForm->isValid($this->getRequest()->getParams())) {

			$className = $fl->getFolderItemClass();
			
			$folderItem = new $className($folderForm->getValues());
			
			$fl->folder()->create($folderItem);

			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Great success');					
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail');
			$msg->errors = $folderForm->getMessages();
		}
		$this->view->message = $msg;
				
	}
	
	
	public function submitAction()
	{
		$filelib = Zend_Registry::get('Emerald_Filelib');
		$form = new EmAdmin_Form_FileUpload();

		if($form->isValid($this->getRequest()->getPost())) {
			$folder = $filelib->folder()->find($form->folder_id->getValue());
			$form->file->receive();
			$file = $filelib->file()->upload($form->file->getFileName(), $folder, $form->profile->getValue());

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
			
			$folder = $fl->folder()->findRoot();
			$iter = new Emerald_Filelib_FolderItemIterator($folder);
			
			$iter = new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::SELF_FIRST);
			
			$this->view->iter = $iter;
			
			
			
			
			
			if($input->id) {
				$activeFolder = $fl->folder()->find($input->id);
				if(!$activeFolder) {
					throw new Emerald_Exception('Folder not found.', 404);
				}
				
				$this->view->folder = $activeFolder;
				$files = $activeFolder->findFiles();
				$this->view->files = $files;

				$folderForm = new EmAdmin_Form_CreateFolder();
				$folderForm->parent_id->setValue($activeFolder->id);
				$this->view->folderForm = $folderForm;
				
				$form = new EmAdmin_Form_FileUpload();
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
	
	
	
	

	public function selectAction()
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
			
			$folder = $fl->folder()->findRoot();
			$iter = new Emerald_Filelib_FolderItemIterator($folder);
			
			$iter = new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::SELF_FIRST);
			
			$this->view->iter = $iter;
			
			if($input->id) {
				$activeFolder = $fl->folder()->find($input->id);
				if(!$activeFolder) {
					throw new Emerald_Exception('Folder not found.', 404);
				}
				
				$this->view->folder = $activeFolder;
				$files = $activeFolder->findFiles();
				$this->view->files = $files;
				
			} else {
				
				$this->getHelper('redirector')->gotoRouteAndExit(array('id' => $folder->id));
				
			}
			
		} catch(Emerald_Exception $e) {
			throw $e;
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e, 500);
		}
		
		
		
	}
	
}
