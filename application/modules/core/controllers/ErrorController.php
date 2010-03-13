<?php
class Core_ErrorController extends Emerald_Controller_Action
{
    public function errorAction()
    {
    	// $this->view->layout()->disableLayout();
    	$errors = $this->_getParam('error_handler');
    	$exception = $errors->exception;
    	    	
    	$this->view->message = $exception->getMessage();

    	
    	$customer = $this->getCustomer();
    	
    	$layout = $customer->getLayout('Error');
    	$layout->setAction($this);
    	$layout->run();
    	
    	
    	switch($errors->type)
    	{
    		
    		case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            	return $this->_forward('not-found');
            	break;
            
            default:
           		
            	if($code = $exception->getCode()) {
            		            		            		
            		if($code == 404) {
            			return $this->_forward('not-found');
            		}
            		
            		
            	}
            	
            	return $this->_forward('internal-server');
    	}
    	    	    	
		
    	$this->view->exception = $exception;
    	
    	
    }
    
    
    
    public function notFoundAction()
    {
    	$this->view->responseCode = 404;
    	$this->getResponse()->setHttpResponseCode(404);
    }
    
    
    public function forbiddenAction()
    {
    	$this->view->responseCode = 401;
    	$this->getResponse()->setHttpResponseCode(401);
    }
	    
    
    public function internalServerAction()
    {
		$this->view->responseCode = 500;
    	$this->getResponse()->setHttpResponseCode(500);
    }
    
    
    
}
