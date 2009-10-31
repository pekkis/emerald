<?php
class FileController extends Emerald_Controller_Action
{
	
	
	/**
	 * Renders a file.
	 *
	 */
	public function renderAction()
	{
		
		$filters = array();
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
			'download' => array('Int')
		);
		
		try {
			
			
			
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();

			$fileTbl = Emerald_Model::get('Filelib_File');
			
			
			if(!$file = $fileTbl->find($input->id)->current()) {
				throw new Emerald_Exception('File does not exist', 404);
			}
			
			
			$folder = $file->getFolder();
			if(!$this->_emerald->getAcl()->isAllowed($this->_emerald->getUser(), $folder, 'read')) {
				throw new Emerald_Exception('Forbidden', 403);							
			}
						
        	// $this->getResponse()->appendBody($msg);

			$path = $file->getPath();
						
			if(!is_readable($path))
				throw new Emerald_Exception('File not found!', 404);
			
			
			readfile($path);

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$this->getResponse()->setHeader('Content-Type', $file->mimetype);
			
			// If download param is set, lets do download instead of rendering.
			// This should be _forward from downloadAction. 
			if($input->download) {
				$this->getResponse()->setHeader('Content-disposition',
				"attachment; filename={$file->name}");

			}
			
			
			
		} catch(Emerald_Exception $e) {
			
			throw $e;
		
		} catch(Exception $e) {

			
			throw new Emerald_Exception('Unknown error', 500);
			
		}
	}
	
	
	/**
	 * Downloads a file with its nice name.
	 *
	 */
	public function downloadAction()
	{
		$this->getRequest()->setParam('download', 1);
		$this->_forward('render');
	}
	
	
}
?>