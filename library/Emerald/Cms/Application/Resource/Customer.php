<?php
/**
 * Customer initialization resource
 * 
 * @author pekkis
 * @package Emerald_Cms_Application
 *
 */
class Emerald_Cms_Application_Resource_Customer extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * @return Emerald_Common_Application_Customer
     */
    public function init()
    {
        // Customer may be defined as env variable. Otherwise it is guessed via http host
        $customer = (getenv('EMERALD_CUSTOMER')) ? getenv('EMERALD_CUSTOMER') : $_SERVER['HTTP_HOST'];

        $path = APPLICATION_PATH . '/../customers/' . $customer ;
        if(is_dir($path)) {
            $customer = new Emerald_Common_Application_Customer(realpath($path));
        }

        if(!($customer instanceof Emerald_Common_Application_Customer)) {
            throw new Emerald_Common_Exception("Customer not found");
        }

        $options = $this->getOptions();

        // If options aren't cached, customer specific options are fetched and merged with baseline options
        if(!isset($options['included'])) {
            $config = $customer->getConfig();
            $config = $config->toArray();
            $config['resources']['customer']['included'] = true;
            $this->getBootstrap()->addOptions($config);
        }

        // Emerald constants are set after the merge
        $options = $this->getBootstrap()->getOptions();
        if(isset($options['emerald']['constant'])) {
            foreach($options['emerald']['constant'] as $key => $value) {
                define('EMERALD_' . $key, $value);
            }
        }

        // Config and customer are conveniently registered
        Zend_Registry::set('Emerald_Config', $options);
        Zend_Registry::set('Emerald_Customer', $customer);
         
        return $customer;

    }
}