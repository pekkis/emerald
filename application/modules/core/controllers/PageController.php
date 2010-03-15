<?php
class Core_PageController extends Emerald_Controller_Action
{
	public $ajaxable = array(
        'view'     => array('html'),
    );

    public $contexts = array(
        'view'     => array('xml'),
    );
    
    
	public function init()
	{
		
		$this->getHelper('ajaxContext')->initContext();
		// $this->getHelper('contextSwitch')->initContext();
	}
	
	
	public function viewAction()
	{
		
		
		$filters = array();
		$validators = array(
			'id' => array(new Zend_Validate_Int(), 'required' => false, 'allowEmpty' => true),
			'beautifurl' => array(new Zend_Validate_Regex('(([a-z]{2,3}(_[A-Z]{2})?)/(.*?))'), 'required' => false, 'allowEmpty' => true),
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
				
				$locale = $page->getLocaleItem();
				
				
				
				$readable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read');
				
				if(!$readable) {
					throw new Emerald_Exception('Forbidden', 401);				
				}
				
				if($page->redirect_id && $page->redirect_id != $page->id) {
					
					$redirectPage = $pageModel->find($page->redirect_id);

					
					
					
					return $this->getHelper('redirector')->gotoUrlAndExit(URL_BASE . '/' . $redirectPage->beautifurl);
											
					
					// $this->_forward('view', 'page', 'core', array('id' => $page->redirect_id ));
				}
				
				
				$locale = $page->getLocaleItem();
				
				// $this->view->pageLocaleObj = $locale;

				$this->view->headTitle()->setSeparator(' - ');
				
				$this->view->headTitle($locale->getOption('title'));
				$this->view->headTitle($page->title, 'PREPEND');
				
				// $this->view->page = $page;
				
				 
				
				$naviModel = new Core_Model_Navigation();
				$navi = $naviModel->getNavigation();

				$navi = $navi->findBy('locale_root', $page->locale);
				
				if(!$navi) {
					throw new Emerald_Exception('Locale multifail', 500);
				}
				
												
				$active = $navi->findBy('uri', URL_BASE . '/' . $page->beautifurl, false);
				if($active) {
					$active->setActive(true);
				}

				
				
				$this->view->getHelper('navigation')->setContainer($navi);		
				
				$tpl = $page->getLayoutObject($this);
				$tpl->setPage($page);
				$tpl->setNoRender(true);
				
				$this->getFrontController()->registerPlugin(new Emerald_Controller_Plugin_Page());

				
				if($this->getHelper('ajaxContext')->getCurrentContext() || $this->getHelper('contextSwitch')->getCurrentContext()) {
					$tpl->runAjax();
					
					
					
				} else {
					$tpl->run();
				}
				

				$this->view->activePage = $page;
												
				
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
