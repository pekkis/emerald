<?php
/**
 * Autoupdates customer's database when Emerald upgrades
 * 
 * @todo This is a concept tryout class...
 * 
 * @author pekkis
 *
 */
class Emerald_Controller_Plugin_AutoUpdater extends Zend_Controller_Plugin_Abstract
{
	public function routeStartup()
	{
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		
		$currentVersion = Emerald_Version::getVersionNumber();
		
		$oldVersion = $bootstrap->getResource('customer')->getOption('version');
		if(!$oldVersion) {
			$oldVersion = 0;
		} 

		if($oldVersion < $currentVersion) {
		
			switch ($oldVersion) {
				case 0:
					$this->_execute(0);
					
							
				
			}
			
		}		
		
	}
	
	protected function _execute($id)
	{
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$db = $bootstrap->getResource('db');
		$customer = $bootstrap->getResource('customer');
		
		$file = file_get_contents(APPLICATION_PATH . '/../docs/schema/update/' . $id . '.sql');
		
		$sqls = explode(';', $file);
		
		$db->beginTransaction();
		try {
			foreach($sqls as $sql) {
				if($sql) {
					$db->query($sql);
					echo $sql;
				}
			}		
			
			$customer->setOption('version', Emerald_Version::getVersionNumber());
			
			$db->commit();
			
		} catch(Exception $e) {
			
			echo $e;
			
			$db->rollBack();
		}
		
		
		
		
	}
	
}