<?php
class Emerald_View_Helper_Emerald extends Zend_View_Helper_Abstract
{
	
	
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
			->addJavascriptFile(URL_BASE . "/lib/core/emerald.js")
			->enable()
			->uiEnable();
			
		$this->view->headScript()
			->prependFile(URL_BASE . '/core/langlib/index/locale/' . Zend_Registry::get('Zend_Locale') . '/format/js');
			
		return $this;
	}
	
	
	public function addStylesheets()
	{
		$this->view->headLink()
			->appendStylesheet(URL_BASE . '/data/customer.css');

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
}
