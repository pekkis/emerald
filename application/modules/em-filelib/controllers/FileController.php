<?php
class EmFilelib_FileController extends Zend_Controller_Action
{
	
	public function renderAction()
	{
		$fl = Zend_Registry::get('Emerald_Filelib');
		
		
		
		$file = $fl->file()->find($this->_getParam('id'));
		
		
		
		
		$version = $this->_getParam('version');

		$download = $this->_getParam('download');
		
		$opts = array();
		
		if($version) {
			$opts['version'] = $version;
		}

		if($download) {
			$opts['download'] = true;
		}
			
		$file->render($this->getResponse(), $opts);

		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

	
	}	
	
	
	
}