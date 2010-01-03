<?php
class Core_PageController extends Emerald_Controller_Action
{
	public $ajaxable = array(
        'view'     => array('html'),
    );
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	
	public function viewAction()
	{
		
		
		$filters = array();
		$validators = array(
			'id' => array(new Zend_Validate_Int(), 'required' => false, 'allowEmpty' => true),
			'beautifurl' => array(new Zend_Validate_Regex('(([a-z]{2,3}(_[A-Z]{2})?)/(.*?))'), 'required' => false, 'allowEmpty' => true),
			'forward' => array(new Zend_Validate_InArray(array(0,1)), 'presence' => 'optional', 'default' => 0) 
		);
		
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());

						
			$pageModel = new Core_Model_Page();
						
			if($beautifurl = $input->beautifurl) {
				$page = $pageModel->findByBeautifurl($beautifurl);
			} elseif($id = $input->id) {
				$page = $pageModel->find($id);
			} else {
				throw new Emerald_Exception('Page not found');
			}
					
			if($page) {
				
				$readable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read');
								
				if(!$readable) {

					die('not readable');
					
					// throw new Emerald_Exception('Forbidden', 401);		
					
					// We don't want infinite page forwarding poop de loops, just one forward.
					if(!$input->forward) {
						
						// We try to find a login page for the current locale. If we do, forward there.
						$pageTbl = Emerald_Model::get('DbTable_Page');
						$loginPage = $pageTbl->fetchRow(
							array(
								'locale = ?' => $page->locale,
								'shard_id = ?' => 9,
							)
						);
						if($loginPage) {
							$this->getResponse()->setHttpResponseCode(401);
							$this->_forward('view', null, null, array('forward' => 1, 'id' => $loginPage->id, 'beautifurl' => $loginPage->beautifurl));
							return;
						}
						
					}
					
					
					throw new Emerald_Exception('Forbidden', 401);				
				}
				
				
				$locale = $page->getLocaleItem();
				
				$this->view->pageLocaleObj = $locale;

				$this->view->headTitle()->setSeparator(' - ');
				
				$this->view->headTitle($locale->getOption('title'));
				$this->view->headTitle($page->title, 'PREPEND');
				
				$this->view->page = $page;
				
				 
				
				$naviModel = new Core_Model_Navigation();
				$navi = $naviModel->getNavigation();
				
				

				$tpl = $page->getLayoutObject($this);
				$tpl->setPage($page);
				$tpl->setNoRender(true);

				
				$this->getFrontController()->registerPlugin(new Emerald_Controller_Plugin_Page());

				
				
				if($this->getHelper('ajaxContext')->getCurrentContext() == 'html') {

					$tpl->runAjax();
										
				} else {

				
					$tpl->run();
					
				}
				
								
				
				// $this->getHelper('viewRenderer')->setNoRender();
				
				
			} else {
				throw new Emerald_Exception('Page not found', 404);			
			}
			
		} catch(Exception $e) {
			
			throw new Emerald_Exception($e->getMessage(), $e->getCode() ? $e->getCode() : 404);
		}
				
			

	}
	
}

?>
