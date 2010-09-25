<?php
class EmAdmin_FileController extends Emerald_Controller_Action
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
        $file = $fl->file()->find($this->_getParam('id'));

        try {
            $fl->file()->delete($file);
            $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::SUCCESS, 'Delete ok.');
        } catch(Exception $e) {
            $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::ERROR, 'Delete failed.');
        }
        $this->view->message = $msg;
    }





}