<?php
/**
 * Emerald View
 * 
 * Provides outer template and other functionality for
 * the view. 
 * 
 * @author mkoh
 * @since day one :)
 */ 
class Emerald_View extends Zend_View
{
	
	public function shard($page, $identifier, $params = array())
	{
		try {
			
			if(!$page instanceof Emerald_Page) {
				$page = Emerald_Page::find($page);
				if(!$page) return ""; // prevents unneseccary template errors
			}
						
			$requestParams = Zend_Controller_Front::getInstance()->getRequest()->getQuery();
			$params = array_merge($requestParams, $params);

			$shard = Emerald_Shard::factory($identifier);
			$action = (isset($params['a'])) ? $params['a'] : Emerald_Shard::getDefaultAction($shard);
			$params['page'] = $page;
												
			return $this->action(
			$action , $shard, 'default', $params
			);
					
		} catch(Exception $e) {
			
			return (Emerald_Server::getInstance()->inProduction()) ? '' : $e->getMessage();
		}
		
	}
	
	
}