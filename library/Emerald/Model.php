<?php
/**
 * Fetches Emerald models for you, ensuring that only one instance per class exists.
 * This ensures, hopefully, that nothing unneeded is queried from DB
 *
 */
class Emerald_Model
{
	
	private static $_models = array();
	
	/**
	 * Returns the specified model
	 *
	 * @param string $name Model class name
	 * @return Zend_Db_Table
	 */
	public static function get($name, $module = 'Core')
	{
				
		$className = $module . '_Model_' . $name;
		if(!isset(self::$_models[$name])) {
			self::$_models[$name] = new $className();
		}
		return self::$_models[$name];
	}
	
	
}
?>