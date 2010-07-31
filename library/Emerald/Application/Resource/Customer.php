<?php
class Emerald_Application_Resource_Customer extends Zend_Application_Resource_ResourceAbstract
{

    public function init()
    {
        // Console tools may define customer with putenv
        $customer = (getenv('EMERALD_CUSTOMER')) ? getenv('EMERALD_CUSTOMER') : $_SERVER['HTTP_HOST'];

        // $this->getBootstrap()->bootstrap('frontcontroller');


        $path = APPLICATION_PATH . '/../customers/' . $customer ;
        if(is_dir($path)) {
            $customer = new Emerald_Application_Customer(realpath($path));
        }

        if(!$customer) {
            throw new Emerald_Exception("Customer not found");
        }

        $options = $this->getOptions();

        if(!isset($options['included'])) {
            $config = $customer->getConfig();
            $config = $config->toArray();
            $config['resources']['customer']['included'] = true;
            $this->getBootstrap()->addOptions($config);
        }

        $options = $this->getBootstrap()->getOptions();
        if(isset($options['emerald']['constant'])) {
            foreach($options['emerald']['constant'] as $key => $value) {
                define('EMERALD_' . $key, $value);
            }
        }

        Zend_Registry::set('Emerald_Config', $options);

        /*
         $front = $this->getBootstrap()->getResource('frontcontroller');
         try {
         $front->addModuleDirectory($customer->getRoot() . '/modules');
         } catch(Exception $e) {
         // There aint no customer specific modules
         }
         */

        Zend_Registry::set('Emerald_Customer', $customer);
         
        return $customer;

    }
}