<?php
class Emerald_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected $_timer = null;
	
	/**
	 * @return Emerald_Timer
	 */
	public function getTimer()
	{
		if(!$this->_timer) {
			$this->_timer = Emerald_Timer::getTimer('emerald');
		}
		return $this->_timer;
	}
	
	
	
	protected function _bootstrap($resource)
	{
		if($resource === null) {

			// if customer is not bootstrapped, we must do it first because it merges conf.	
			$this->getTimer()->time("bootstrap '{$resource}' start");
			if(!isset($this->_run['customer'])) {
				$this->bootstrap('customer');
			}
			
			$ret = parent::_bootstrap($resource);
			$this->getTimer()->time("bootstrap '{$resource}' end");
			return $ret;
				
		}
		
		$this->getTimer()->time("bootstrap '{$resource}' start");
		$ret = parent::_bootstrap($resource);
		$this->getTimer()->time("bootstrap '{$resource}' end");
		
		return $ret;
	}
	
	
	protected function _executeResource($resource)
	{
     	$resourceName = strtolower($resource);
        if (in_array($resourceName, $this->_run)) {
            return;
        }
		
		$this->getTimer()->time("execute '{$resource}' start");
		parent::_executeResource($resource);
		$this->getTimer()->time("execute '{$resource}' end");
				
	}
	
	
	
	public function getRun()
	{
		return $this->_run;
	}
	
	
	
	
	public function addOptions($options)
	{
	    return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
	}
	
	
}