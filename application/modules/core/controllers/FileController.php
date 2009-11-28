<?php
class Core_FileController extends Emerald_Controller_Action
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
			if(!Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $folder, 'read')) {
				throw new Emerald_Exception('Forbidden', 403);							
			}
						
        	// $this->getResponse()->appendBody($msg);

			$path = $file->getPathname();
						
			if(!is_readable($path))
				throw new Emerald_Exception('File not found!', 404);
			
			
			readfile($path);

			$response->setHeader('Content-Type', $file->mimetype);
			
			
			
		} catch(Exception $e) {
			
			throw $e;
		
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