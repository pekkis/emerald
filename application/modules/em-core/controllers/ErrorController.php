<?php
class EmCore_ErrorController extends Emerald_Controller_Action
{
    const RETRY_AFTER_SECONDS = 120;
	
	public function errorAction()
    {
    	$errors = $this->_getParam('error_handler');
    	$exception = $errors->exception;
    	
    	switch($errors->type)
    	{
    		// In case of internal ZF not found exceptions, fallback and try to find an Emerald page.
	   		case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
   			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
   			
   			$beautifurl = ltrim($this->getRequest()->getRequestUri(), '/');
   			$pageModel = new EmCore_Model_Page();
   			$page = $pageModel->findByBeautifurl($beautifurl);
   			if($page) {
   				return $this->_forward('view', 'page', 'em-core', array('id' => $page->id));
   			} else {
   				$this->view->message = $exception->getMessage();
		    	$customer = $this->getCustomer();
		    	$layout = $customer->getLayout('Error');
		    	$layout->setAction($this);
		    	$layout->run();
		    	$this->view->exception = $exception;
		    	return $this->_forward('not-found');
   			}
           	break;
            
            default:
            	$this->view->message = $exception->getMessage();
		    	$this->view->exception = $exception;
            	if($code = $exception->getCode()) {
            		// fixed code below to match http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
					// 401 is unauthorized(and such, requires WWW-Authenticate header field to be sent)
					// but still handling it wrong for backwards compat.
					// 403 is forbidden
            		if($code == 404) {
            			return $this->_forward('not-found');
					} elseif($code == 401) {
						return $this->_forward('forbidden');
            		} elseif($code == 403) {
            			
            			// If admin and forbidden, redirect to login.
            			if($this->_getParam('module') == 'em-admin') {
            				return $this->_forward('index', 'login', 'em-core');
            			}
            			return $this->_forward('forbidden');
            		} elseif($code == 503) {
            			return $this->_forward('maintenance');
            		}
            	}
            	return $this->_forward('internal-server');
    	}
    }
    
    
    public function notFoundAction()
    {
    	$this->view->responseCode = 404;
    	$this->getResponse()->setHttpResponseCode(404);
    	$this->_runErrorLayout();
    }
    
    
    public function forbiddenAction()
    {
    	$this->view->responseCode = 403;
    	$this->getResponse()->setHttpResponseCode(403);
    	$this->_runErrorLayout();
    }
	    
    
    public function internalServerAction()
    {
    	$this->view->responseCode = 500;
    	$this->getResponse()->setHttpResponseCode(500);
    	$this->_runErrorLayout();
    }
    

    public function maintenanceAction()
    {
    	$this->view->responseCode = 503;
    	$this->getResponse()->setHttpResponseCode(503);
    	
    	$config = Zend_Registry::get('Emerald_Config');
    	if(isset($config['emerald']['maintenance']['retryAfter'])) {
    		$retryAfter = $config['emerald']['maintenance']['retryAfter'];
    	} else {
    		$retryAfter = self::RETRY_AFTER_SECONDS;
    	}
    	
    	$this->getResponse()->setHeader('Retry-After', $retryAfter);
    	$this->_runErrorLayout();
    	
    }
   
    
    
    private function _runErrorLayout()
    {
 		$customer = $this->getCustomer();
		$layout = $customer->getLayout('Error');
		$layout->setAction($this);
		$layout->run();
    	
    }
    
}