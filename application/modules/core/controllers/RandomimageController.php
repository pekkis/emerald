<?php
class Emerald_RandomImageController extends Emerald_Controller_Action 
{
			
	public function indexAction()
	{
		$filters = array(
		);
		$validators = array(
			'folder_id' => array('Digits', 'presence' => 'required', 'allowEmpty' => false),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();


			$fileTbl = Emerald_Model::get('Filelib_File');
						
			$files = $fileTbl->fetchAll(array('folder_id = ?' => $input->folder_id));
			$filesArr = $files->toArray();
			
			$file = $files[array_rand($filesArr)];

			$this->view->file = $file;
			
						
		} catch(Exception $e) {
			
			throw $e;
		}
	}
	
	
}
