<?php
/**
 * Permissions
 * 
 * @package Emerald_Permission
 * @author pekkis
 *
 */
class Emerald_Permission
{
    const EXECUTE = 1;
    const WRITE = 2;
    const READ = 4;

    /**
     * Permissions
     * @var array
     */
    private static $_permissions = array(
        self::EXECUTE => 'execute',
        self::WRITE => 'write',
        self::READ => 'read',
    );

    /**
     * Returns all permissions
     * 
     * @return array
     */
    public static function getAll()
    {
        return self::$_permissions;
    }


    /**
     * Returns a human-readable name for a permission id
     * @param integer $id
     * @return string
     */
    public static function getName($id)
    {
        return self::$_permissions[$id];
    }

}
?>