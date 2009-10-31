<?php
/**
 * This class works with Emerald shards
 *
 */
class Emerald_Shard
{
	
	/**
	 * Hash of shard names/ids
	 *
	 * @var array
	 */
	static private $_shardNames = array();

	
	/**
	 * Hash of shard names/ids
	 *
	 * @var array
	 */
	static private $_shardActions = array();
		
	/**
	 * Hash of shard ids/names
	 *
	 * @var array
	 */
	static private $_shardIds = array();
	
	/**
	 * Cached concrete shard classes
	 *
	 * @var array
	 */
	static private $_shards = array();
	
	
	/**
	 * Manufactures sumptin.
	 *
	 * @param mixed $identifier shard id or name
	 * @return Emerald_shard_Renderer_Abstract Emerald shard renderer
	 */
	public static function factory($identifier)
	{
		$db = Emerald_Application::getInstance()->getDb();
		
		if(!self::$_shardIds) {

			$shards = $db->fetchAll("SELECT id, name, action FROM shard WHERE status & 1");
			foreach($shards as $srv) {
				self::$_shardIds[$srv->id] = $srv->name;
				self::$_shardActions[$srv->name] = $srv->action;
			}
			self::$_shardNames = array_flip(self::$_shardIds);
		}
		
		if(is_numeric($identifier)) {
			if(!isset(self::$_shardIds[$identifier])) {
				throw new Emerald_Exception('Unknown shard');
			}

			$identifier = self::$_shardIds[$identifier];
		}

		if(!isset(self::$_shardNames[$identifier])) {
				throw new Emerald_Exception('Unknown shard');
		}
		
		return $identifier;
	}
	
	
	public function getDefaultAction($shardName)
	{
		return self::$_shardActions[$shardName];
	}
	
	
}
