<?php
class Emerald_ErrorController extends Emerald_Controller_Action
{
    public function errorAction()
    {
    	$this->view->layout()->disableLayout();
    	$errors = $this->_getParam('error_handler');
    	$exception = $errors->exception;
    			
    	
    	
    	switch($errors->type)
    	{
    		
    		case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            	$this->getResponse()->setHttpResponseCode(404);
            	break;
            
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
            	
            	if($exception instanceof Emerald_Exception) {
            		$this->getResponse()->setHttpResponseCode($exception->getHttpResponseCode());	
            	} else {
            		$this->getResponse()->setHttpResponseCode(500);
            	}
            	break;
    		
    		
    	}
    	    	    	
		$this->view->message = $exception->getMessage();
    	$this->view->exception = $exception;
    	
    	
    }
}
?>