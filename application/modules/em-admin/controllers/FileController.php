<?php
class EmAdmin_FileController extends Emerald_Cms_Controller_Action
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
            $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::SUCCESS, 'Delete ok.');
        } catch(Exception $e) {
            $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::FAILURE, 'Delete failed.');
        }
        $this->view->message = $msg;
    }





}