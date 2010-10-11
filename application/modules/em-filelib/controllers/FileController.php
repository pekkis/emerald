<?php
class EmFilelib_FileController extends Zend_Controller_Action
{

    public function renderAction()
    {
        $fl = Zend_Registry::get('Emerald_Filelib');

        $file = $fl->file()->find($this->_getParam('id'));
        if(!$file) {
            throw new Emerald_Common_Exception('File not found', 404);
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
        
        if($fl->file()->isAnonymousReadable($file)) {
            $url = $fl->file()->getUrl($file, $opts);
            return $this->getResponse()->setRedirect($url, 302);
        }
        
        // Convert all exceptions to 404's
        try {
            
            if(isset($opts['download'])) {
                $this->getResponse()->setHeader('Content-disposition', "attachment; filename={$file->getName()}");
            }
        
            $this->getResponse()->setHeader('Content-Type', $file->getMimetype());
            
            $fl->file()->render($file, $opts);
            
        } catch(Exception $e) {
            throw new Emerald_Common_Exception('File not found', 404);
        }

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

    }



}