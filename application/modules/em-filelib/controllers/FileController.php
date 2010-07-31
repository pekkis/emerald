<?php
class EmFilelib_FileController extends Zend_Controller_Action
{

    public function renderAction()
    {
        $fl = Zend_Registry::get('Emerald_Filelib');

        $file = $fl->file()->find($this->_getParam('id'));
        if(!$file) {
            throw new Emerald_Exception('File not found', 404);
        }

        $version = $this->_getParam('version');
        $download = $this->_getParam('download');
        $opts = array();

        if($version) {
            $opts['version'] = $version;
        }
        if($download) {
            $opts['download'] = true;
        }

        // Convert all exceptions to 404's
        try {
            $file->render($this->getResponse(), $opts);
        } catch(Exception $e) {
            throw new Emerald_Exception('File not found', 404);
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

    }



}