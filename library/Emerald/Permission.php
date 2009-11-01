<?php
/**
 * Emerald permission
 *
 */
class Emerald_Permission
{
	const EXECUTE = 1;
	const WRITE = 2;
	const READ = 4;
		
	const PUBLISH = 8;
	
	
	private static $_permissions = array(
		1 => 'execute',
		2 => 'write',
		4 => 'read',
		8 => 'publish',
	);
	
	public static function getAll()
	{
		return self::$_permissions;	
	}
	
	
	public static function getName($id)
	{
		return self::$_permissions[$id];
	}
	
}
?>