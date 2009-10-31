<?php
class Emerald_Js
{

	static public function addAdminScripts(Zend_View_Abstract $view)
	{
		$view->headScript()->prependFile('/admin/langlib/js/id/' . Emerald_Application::getInstance()->getLocale());
		$view->headScript()->prependFile('/lib/js/tinymce/jscripts/tiny_mce/tiny_mce.js');
		$view->headScript()->prependFile('/lib/js/addon.js');
		$view->headScript()->prependFile('/lib/js/common.js');
		$view->headScript()->prependFile('/lib/js/prototype/prototype.js');
		$view->headScript()->prependScript('var $j = jQuery.noConflict();');
		$view->headScript()->prependFile('/lib/js/jquery/jquery.intercept-min.js');
		$view->headScript()->prependFile('/lib/js/jquery/jquery.listen-min.js');
		$view->headScript()->prependFile('/lib/js/jquery/jquery.js');
		$view->headScript()->prependFile('/lib/js/firebug/firebugx.js');
	}
	
	
	static public function addjQueryUi(Zend_View_Abstract $view)
	{
		
		$view->headLink()->appendStylesheet('/lib/js/jquery/theme/jquery-ui-themeroller.css');
		$view->headScript()->appendFile('/lib/js/jquery/jquery.ui.js');
	}
	
	
	
}
