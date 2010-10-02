<?php
class EmAdmin_FolderController extends Emerald_Controller_Action
{
    public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }


    public function deleteAction()
    {
        	
        $fl = Zend_Registry::get('Emerald_Filelib');
        $folder = $fl->folder()->find($this->_getParam('id'));


        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $folder, 'write')) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        try {
            $fl->folder()->delete($folder);
            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::SUCCESS, 'Folder was deleted.');
        } catch(Exception $e) {
            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR, 'Folder delete failed.');
        }

        $this->view->message = $msg;

    }


    public function editAction()
    {

        $fl = Zend_Registry::get('Emerald_Filelib');
        $folder = $fl->folder()->find($this->_getParam('id'));

        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $folder, 'write')) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


        $form = new EmAdmin_Form_Folder();
        $form->setDefaults($folder->toArray());

        $folderModel = new EmCore_Model_Folder();

        $permForm = $form->getSubForm('folder-permissions');
        $permissions = $folderModel->getPermissions($folder);

        $permForm->setDefaults($permissions);

        $this->view->form = $form;


    }




    public function saveAction()
    {
        $form = new EmAdmin_Form_Folder();

        if($form->isValid($this->_getAllParams())) {

            $fl = Zend_Registry::get('Emerald_Filelib');
            $folder = $fl->folder()->find($form->id->getValue());

            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $folder, 'write')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }

            $folder->name = $form->name->getValue();

            // $folder->setFromArray($form->getValues());
            	
            $fl->folder()->update($folder);

            $folderModel = new EmCore_Model_Folder();
            $folderModel->savePermissions($folder, $form->getSubForm('folder-permissions')->getValues());
            	
            $fl->folder()->update($folder);
            	
            // $this->getAcl()->cacheRemove();
            	
            $this->view->message = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::SUCCESS, 'Save ok!');
            $this->view->message->folder_id = $folder->id;
            	
        } else {
            $msg = new Emerald_Common_Messaging_Message(Emerald_Common_Messaging_Message::ERROR, 'Save failed');
            $msg->errors = $form->getMessages();
            $this->view->message = $msg;
        }



    }


}