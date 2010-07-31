<?php
class Emerald_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _bootstrap($resource = null)
    {
        if($resource === null) {
            if(!isset($this->_run['customer'])) {
                $this->bootstrap('customer');
            }
            $ret = parent::_bootstrap($resource);
            return $ret;

        }

        $ret = parent::_bootstrap($resource);
    }

    public function addOptions($options)
    {
        return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
    }


}