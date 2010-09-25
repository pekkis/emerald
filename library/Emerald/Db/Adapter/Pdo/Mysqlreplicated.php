<?php
/**
 * Prototype replicated PDO Mysql adapter
 * 
 * @author pekkis
 * @package Emerald_Db
 *
 */
class Emerald_Db_Adapter_Pdo_Mysqlreplicated extends Zend_Db_Adapter_Pdo_Mysql
{

    protected $_inTransaction = 0;

    protected $_slaves = array();

    protected $_slave;

    public function __construct($config)
    {


        $this->_slaves = $config['slave'];
        unset($config['slave']);

        foreach($this->_slaves as &$slave) {
            $slave = array_merge($config, $slave);
        }

        parent::__construct($config);

    }


    public function getRandomSlave()
    {
        if(!$this->_slave) {

            $rand = array_rand($this->_slaves);
            $slave = $this->_slaves[$rand];
            $this->_slave = Zend_Db::factory('pdo_mysql', $slave);
        }
        return $this->_slave;
    }


    public function query($sql, $bind = array())
    {
        // use the slave on SELECT statements, but only when we're not in
        // a transaction, and also not in a GET-after-POST request.
        $useSlave = strtoupper(substr($sql, 0, 6)) == 'SELECT'
        && ! $this->_inTransaction;


        if($useSlave) {
            return $this->getRandomSlave()->query($sql, $bind);
        }

        return parent::query($sql, $bind);

    }


    public function beginTransaction()
    {
        parent::beginTransaction();
        $this->_inTransaction++;
    }



    public function rollBack()
    {
        parent::rollBack();
        $this->_inTransaction--;
    }


    public function commit()
    {
        parent::commit();
        $this->_inTransaction--;
    }


    public function quote($value, $type = null)
    {
        return $this->getRandomSlave()->quote($value, $type);
    }





}

