<?php
class Core_View_Helper_Emerald extends Zend_View_Helper_Abstract
{
	
	private $_user;
	
	private $_acl;
	
	
	public function emerald()
	{
		return $this;
	}
	
	
	public function addEverything()
	{
		return $this->addMeta()->addScripts()->addStylesheets();
	}
	
	
	public function addMeta()
	{
		$this->view->headMeta()
			->appendHttpEquiv("Content-Type", "text/html; charset=UTF-8")
			->appendName("Generator", "Emerald");
		return $this;
	}
	
	
	public function addScripts()
	{
		$this->view->jQuery()
			->setCdnVersion('1.4')
			->setUiCdnVersion('1.7.2')
			->addJavascriptFile(EMERALD_URL_BASE_LIB . '/lib/core/emerald.js')
			->addJavascriptFile(EMERALD_URL_BASE_LIB . '/lib/ext/jquery.jGrowl.js')
			->enable()
			->uiEnable();
			
		$this->view->headScript()
			->prependFile(EMERALD_URL_BASE . '/core/langlib/index/locale/' . Zend_Registry::get('Zend_Locale') . '/format/js');
			
		return $this;
	}
	
	
	public function addStylesheets()
	{
		$this->view->headLink()
			->appendStylesheet(EMERALD_URL_BASE_LIB . '/lib/ext/jquery.jGrowl.css')
			->appendStylesheet(EMERALD_URL_BASE_DATA . '/data/customer.css');

		return $this;
	}
	
	
	public function googleAnalytics()
	{
		$analyticsId = Zend_Registry::get('Emerald_Customer')->getOption('google_analytics_id');
		if($analyticsId) {
			$xhtml = '<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("' . $analyticsId . '");
pageTracker._initData();
pageTracker._trackPageview();
</script>';

		return $xhtml;
		
		}
	
	}
	
	
	
	public function getUser()
	{
		if(!$this->_user) {
			$this->_user = Zend_Registry::get('Emerald_User');
		}
		return $this->_user;
	}
	
	
	public function getAcl()
	{
		if(!$this->_acl) {
			$this->_acl = Zend_Registry::get('Emerald_Acl');
		}
		return $this->_acl;
	}
	
	
	
	public function userIsAllowed($resource, $privilege = null)
	{
		if(!$resource) {
			return false;
		}
		
		$user = $this->getUser();
		$acl = $this->getAcl();
		
		return $acl->isAllowed($user, $resource, $privilege);
		
	}
	
	
	public function findActivity($category, $name)
	{
		static $model;
		if(!$model) {
			$model = new Admin_Model_Activity();
		}
		
		return $model->findByCategoryAndName($category, $name);
		
	}
	
	
	
}
