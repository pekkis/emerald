<?php
class Admin_LocaleController extends Emerald_Controller_AdminAction 
{
	public function indexAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
		
		$localeTbl = Emerald_Model::get('DbTable_Locale');
		
		$selectedLocalesRaw = $localeTbl->fetchAll();
		
		
										
		$selectedLocales = array();
		foreach($selectedLocalesRaw as $localeRaw) {
			$selectedLocales[] = $localeRaw->locale;			
		}
			
				
		$availableLocales = array_keys(Zend_Locale::getLocaleList());
		$locale = Zend_Registry::get('Zend_Locale');

		$workLocale = new Zend_Locale();
		
		$forbiddenLocales = array('root', 'auto', 'environment', 'browser', 'sr_YU', 'und', 'und_ZZ');
	
		
		$usualLocales = array('fi', 'fi_FI', 'sv', 'sv_SE', 'en', 'en_US', 'en_UK'); 
		$this->view->usualLocales = $usualLocales;
		
		$this->view->locale = $locale;
		$this->view->workLocale = $workLocale;		
		$this->view->forbiddenLocales = $forbiddenLocales;
		$this->view->selectedLocales = $selectedLocales;
		$this->view->availableLocales = $availableLocales;
		
		
		$this->view->headScript()->appendFile('/lib/js/admin/locale/index.js');
		$this->view->headLink()->appendStylesheet('/lib/css/admin/locale/locale.css');
		
	}
	
	
	/**
	 * This is The Most Dangerous Action Alive(tm). User can wipe out all data from all the pages
	 * of all the locales with a click of a button. Thank you cascading deletes! :)
	 * 
	 * @todo Implement ACL, only root can do. 
	 * @todo Maybe change the cascading in Locale table to restrict?
	 * @todo Maybe implement status for locale, just update it until janitor deletes from db?!?
	 *
	 */
	public function updateAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
		$filters = array();
		
		$validators = array(
			'locale' => 'Int'
		);

		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$msg = Zend_Registry::get('Zend_Translate')->_('Save ok.');
			
			
			$selectedLocales = array();
			
			foreach($input->locale as $key => $value) {
				if($value == 1)
					$selectedLocales[] = $key;											
			}
			
			
			$localeTbl = Emerald_Model::get('DbTable_Locale');
			$localesRaw = $localeTbl->fetchAll();
			
			$existingLocales = array();
			foreach($localesRaw as $localeRaw) {
				$existingLocales[] = $localeRaw->locale;
			}
			
			$addLocales = array_diff($selectedLocales, $existingLocales);
			$deleteLocales = array_diff($existingLocales, $selectedLocales);
			
			
			$localeTbl->getAdapter()->beginTransaction();
			
			if($deleteLocales) {
				$localeTbl->delete($localeTbl->getAdapter()->quoteInto("locale IN (?)", $deleteLocales));
			}
			foreach($addLocales as $addLocale) {
				$localeTbl->insert(array('locale' => $addLocale));
			}
			
			$localeTbl->getAdapter()->commit();
			
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'l:admin/locale/update_ok');
									
		} catch(Exception $e) {
			
			$localeTbl->getAdapter()->rollback();
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'l:admin/locale/update_failed');
						
			
		}
		
		
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
		
	}
	
	
}