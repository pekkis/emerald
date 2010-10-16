<?php
class EmAdmin_FilelibController extends Emerald_Cms_Controller_Action
{

    public $ajaxable = array(
		'create-folder' => array('json'),
		'delete-folder' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }


    public function recreateSymlinksAction()
    {
        $fl = Zend_Registry::get('Emerald_Filelib');

        foreach($fl->file()->findAll() as $file) {
            	
            $file->getProfileObject()->getLinker()->deleteSymlink($file);
            $file->getProfileObject()->getLinker()->createSymlink($file);
            	
            $plugins = $file->getProfileObject()->getPlugins();
            foreach($plugins as $plugin) {
                if($plugin instanceof \Emerald\Filelib\Plugin\_VersionPlugin) {
                    $plugin->deleteSymlink($file);
                    $plugin->createSymlink($file);
                }


            }
            	
            	
        }

        die('xooxer');

    }



    public function createFolderAction()
    {
        $folderForm = new EmAdmin_Form_CreateFolder();

        $fl = Zend_Registry::get('Emerald_Filelib');

        if($folderForm->isValid($this->getRequest()->getParams())) {

            $parentFolder = $fl->folder()->find($folderForm->parent_id->getValue());
            	
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $parentFolder, 'write')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }

            $className = $fl->getFolderItemClass();
            $folderItem = new $className();
            $folderItem->fromArray($folderForm->getValues());
                                    	
            $fl->folder()->create($folderItem);

            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::SUCCESS, 'Folder creation ok.');
            	
        } else {
            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR, 'Folder creation failed.');
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
            	
            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $folder, 'write')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            	
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
			'id' => array('default_value' => null)
        );


        try {
            	
            $input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
            $input->process();

            $fl = Zend_Registry::get('Emerald_Filelib');
            $this->view->fl = $fl;
            	
            $folder = $fl->folder()->findRoot();
            
            $iter = new Emerald\Filelib\FolderIterator($folder);
            	
            $iter = new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::SELF_FIRST);
            	
            $this->view->iter = $iter;

            	
            if($input->id) {
                $activeFolder = $fl->folder()->find($input->id);
                if(!$activeFolder) {
                    throw new Emerald_Common_Exception('Folder not found.', 404);
                }

                if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $activeFolder, 'read')) {
                    throw new Emerald_Common_Exception('Forbidden', 403);
                }


                $this->view->folder = $activeFolder;
                $files = $fl->folder()->findFiles($activeFolder); 
                                
                $this->view->files = $files;

                $folderForm = new EmAdmin_Form_CreateFolder();
                $folderForm->parent_id->setValue($activeFolder->getId());
                $this->view->folderForm = $folderForm;

                $form = new EmAdmin_Form_FileUpload();
                $form->folder_id->setValue($activeFolder->getId());
                $this->view->form = $form;

            } else {

                $this->getHelper('redirector')->gotoRouteAndExit(array('id' => $folder->getId()));

            }
            	
            	
            	
        } catch(Emerald_Common_Exception $e) {
            throw $e;
            	
        } catch(Exception $e) {
            throw new Emerald_Common_Exception($e, 500);
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
            $iter = new Emerald\Filelib\FolderIterator($folder);
            	
            $iter = new RecursiveIteratorIterator($iter, RecursiveIteratorIterator::SELF_FIRST);
            	
            $this->view->iter = $iter;
            	
            if($input->id) {
                $activeFolder = $fl->folder()->find($input->id);
                if(!$activeFolder) {
                    throw new Emerald_Common_Exception('Folder not found.', 404);
                }

                $this->view->folder = $activeFolder;
                $files = $fl->folder()->findFiles($activeFolder);
                $this->view->files = $files;

            } else {

                $this->getHelper('redirector')->gotoRouteAndExit(array('id' => $folder->getId()));

            }
            	
        } catch(Emerald_Common_Exception $e) {
            throw $e;
            	
        } catch(Exception $e) {
            throw new Emerald_Common_Exception($e, 500);
        }



    }

}
