<?php
/**
 * Index controller tries to find page, any page, to forward to. When it does not find,
 * it throws an exception. Maybe they could automagically be handled?!?
 *
 */
class IndexController extends Emerald_Controller_Action
{
	/**
	 * Many come here. Some get forwarded, others get thrown as exceptions.
	 *
	 */
	public function indexAction()
	{
						
		$filters = array();
		
		$validators = array(
			'locale' => new Zend_Validate_Regex('([a-z]{2,3}(_[A-Z]{2})?)')
		);
		
		try {
			
			$filtered = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$filtered->process();
									
			$localeTbl = Emerald_Model::get('Locale');

			$locale = false;
			
			// If a locale is present, use it. If not, dig for default locale in app options.
			if($selectedLocale = $filtered->locale) {
				$locale = $localeTbl->find($filtered->locale)->current();
				if(!$locale) {
					throw new Exception("Locale '{$selectedLocale}' not found.");
				}
			} elseif($defaultLocale = $this->getCustomer()->getOption('default_locale')) {
				$locale = $localeTbl->find($defaultLocale)->current();
			}
					
			
			// Still no locale, get first locale from db and make it default.
			if(!$locale) {
				$locale = $localeTbl->fetchAll(null, 'locale', 1, 0)->current();
				if($locale) {
					$this->getCustomer()->setOption('default_locale', $locale->locale);
				}
			}
									
			// Still no locales. There simply isn't any locales in db.
			if(!$locale) {
				throw new Exception('No locales found.');
			}
			
			
			
			
			$pageTbl = Emerald_Model::get('Page');
			$pageStart = $locale->page_start;
			$row = false;
						
			// If a start page is defined in locale, use it.
			if($pageStart) {

				$row = $pageTbl->find($pageStart)->current();
				
				// $where = array('id' => $pageStart);
				// $row = $pageTbl->fetchRow($where, 'id ASC');
			}
									
			// Still no start page, get ANY page for the locale.
			if(!$row) {
				$where = array('locale = ?' => $locale->locale);
				$row = $pageTbl->fetchRow($where, 'id ASC');
			}
									
			
			// Sitemap must be empty because theres still no page.
			if(!$row) {
				throw new Emerald_Exception('No pages found.');
			}

			
			// Lets forward instead of redirecting. Url looks easier(tm).
			$this->_forward('view', 'page', null, array('iisiurl' => $row->iisiurl));
					
		} catch(Exception $e) {
			// Something went badly wrong. No can continunado :(			
			throw new Emerald_Exception($e->getMessage());
		}
	}
	
}
?>