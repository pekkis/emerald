<?php
class EmCore_ErrorController extends Emerald_Controller_Action
{
    public function errorAction()
    {
    	$errors = $this->_getParam('error_handler');
    	$exception = $errors->exception;
    	    	    	
    	switch($errors->type)
    	{
    		
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
		    	$customer = $this->getCustomer();
		    	$layout = $customer->getLayout('Error');
		    	$layout->setAction($this);
		    	$layout->run();
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
            			return $this->_forward('forbidden');
            		}
            		
            		
            	}
            	
            	return $this->_forward('internal-server');
    	}

    	
    	
    	
    	    	
    	
    	
    }
    
    
    
    public function notFoundAction()
    {
    	$this->view->responseCode = 404;
    	$this->getResponse()->setHttpResponseCode(404);
    }
    
    
    public function forbiddenAction()
    {
    	$this->view->responseCode = 403;
    	$this->getResponse()->setHttpResponseCode(403);
    }
	    
    
    public function internalServerAction()
    {
    	$this->view->responseCode = 500;
    	$this->getResponse()->setHttpResponseCode(500);
    }
    
    
    
}
