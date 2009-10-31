<?php
class Emerald_View_Helper_GoogleAnalytics
{
		
	public function googleAnalytics()
	{
		$analyticsId = Emerald_Application::getInstance()->getOption('google_analytics_id');
				
		
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
?>