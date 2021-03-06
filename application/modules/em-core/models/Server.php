<?php
class EmCore_Model_Server
{
    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }


    public function registerCustomer(Emerald_Common_Application_Customer $customer)
    {
        $table = new EmCore_Model_Server_DbTable_Customer($this->_db);

        try {
            $table->insert(array('identifier' => $customer->getIdentifier()));
        } catch(Exception $e) {
            throw new Emerald_Common_Exception(500, "Could not register customer '{$customer->getIdentifier()} to server.'");
        }

        $customer->setOption('registered', 1);
        return true;

    }




}
