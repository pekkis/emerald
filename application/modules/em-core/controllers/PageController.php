<?php
class EmCore_PageController extends Emerald_Cms_Controller_Action
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
            $input->setDefaultEscapeFilter(new Emerald_Common_Filter_HtmlSpecialChars());

            $pageModel = new EmCore_Model_Page();
            	
            // If beautifurl is available, it's always used.
            if($beautifurl = $input->beautifurl) {
                $page = $pageModel->findByBeautifurl($beautifurl);
            } elseif($id = $input->id) {
                $page = $pageModel->find($id);
            } else {
                throw new Emerald_Common_Exception('Page not found', 404);
            }
            	
            // No page naturally throws 404 exception.
            if(!$page) {
                throw new Emerald_Common_Exception('Page not found', 404);
            }

            $locale = $page->getLocaleItem();

            // Check permissions
            $readable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read');
            if(!$readable) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }

            // Check for redirect flag and redirect when present
            if($page->redirect_id && $page->redirect_id != $page->id) {
                $redirectPage = $pageModel->find($page->redirect_id);
                return $this->getHelper('redirector')->gotoUrlAndExit(EMERALD_URL_BASE . '/' . $redirectPage->beautifurl);
            }

            $naviModel = new EmCore_Model_Navigation();
            $navi = $naviModel->getNavigation();

            // Activate current page in zend navi
            $navi = $navi->findBy('locale_root', $page->locale);
            if(!$navi) {
                throw new Emerald_Common_Exception('Locale multifail', 500);
            }
            $active = $navi->findBy('uri', EMERALD_URL_BASE . '/' . $page->beautifurl, false);
            if($active) {
                $active->setActive(true);
            }

            // Set navigation helper container
            $this->view->getHelper('navigation')->setContainer($navi);

            // Initialize Emerald layout
            $tpl = $page->getLayoutObject($this);
            $tpl->setPage($page);
            $tpl->setNoRender(true);
                        
            // Register
            $this->getFrontController()->registerPlugin(new Emerald_Cms_Controller_Plugin_Page());

            if($this->getHelper('ajaxContext')->getCurrentContext() || $this->getHelper('contextSwitch')->getCurrentContext()) {
                $tpl->runAjax();
            } else {
                $tpl->run();
            }

            $this->view->activePage = $page;
            	
        } catch(Exception $e) {
            throw new Emerald_Common_Exception($e->getMessage(), $e->getCode() ? $e->getCode() : 404);
        }


    }

}

?>
