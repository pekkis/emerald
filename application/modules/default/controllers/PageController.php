<?php
class PageController extends Emerald_Controller_Action
{
	
	public function viewAction()
	{
		
		
		$filters = array();
		$validators = array(
			'id' => array(new Zend_Validate_Int()),
			'iisiurl' => array(new Zend_Validate_Regex('(([a-z]{2,3}(_[A-Z]{2})?)/(.*?))')),
			'forward' => array(new Zend_Validate_InArray(array(0,1)), 'presence' => 'optional', 'default' => 0) 
		);
		
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			if($iisiUrl = $input->iisiurl) {
				$page = Emerald_Page::findByIisiUrl(urldecode($iisiUrl));
			} elseif($id = $input->id) {
				$page = Emerald_Page::find($id);
			} else {
				throw new Emerald_Exception('Falseful page identifier!');
			}
					
			if($page) {
				
				$readable = $this->_emerald->getAcl()->isAllowed($this->_emerald->getUser(), $page, 'read');
								
				if(!$readable) {

					// throw new Emerald_Exception('Forbidden', 401);		
					
					// We don't want infinite page forwarding poop de loops, just one forward.
					if(!$input->forward) {
						
						// We try to find a login page for the current locale. If we do, forward there.
						$pageTbl = Emerald_Model::get('page');
						$loginPage = $pageTbl->fetchRow(
							array(
								'locale = ?' => $page->locale,
								'shard_id = ?' => 9,
							)
						);
						if($loginPage) {
							$this->getResponse()->setHttpResponseCode(401);
							$this->_forward('view', null, null, array('forward' => 1, 'id' => $loginPage->id, 'iisiurl' => $loginPage->iisiurl));
							return;
						}
						
					}
					
					
					throw new Emerald_Exception('Forbidden', 401);				
				}
				
				$localeTbl = Emerald_Model::get('Locale');
				$locale = $localeTbl->find($page->getLocale()->toString())->current();
				$this->view->pageLocaleObj = $locale;

				$this->view->headTitle()->setSeparator(' - ');
				
				$this->view->headTitle($locale->getOption('title'));
				$this->view->headTitle($page->title, 'PREPEND');
				
				$this->view->page = $page;
												
				$this->_helper->layout->setLayout('templates/' . basename($page->template, '.phtml'));
				$this->_helper->viewRenderer->setRender('innertemplates/' . basename($page->innertemplate, '.phtml'), null, true);
				$this->getFrontController()->registerPlugin(new Emerald_Controller_Plugin_Page());
				
			} else {
				throw new Emerald_Exception('Page not found', 404);			
			}
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), $e->getHttpResponseCode() ? $e->getHttpResponseCode() : 404);
		}
				
			

	}
	
}

?>
