<?php
/**
 * Crude 1st generation autoloader for default Emerald ACL resources (based on legacy hard-code)
 * 
 * @author pekkis
 * @package Emerald_Acl
 *
 */
class Emerald_Acl_Autoloader
{
    public function autoloadResource($resource)
    {
        if(preg_match("/^Emerald_Page/", $resource)) {
            $split = explode("_", $resource, 3);
            $pageModel = new EmCore_Model_Page();
            $resource = $pageModel->find($split[2]);
        } else if(preg_match("/^Emerald_Locale/", $resource)) {
            $split = explode("_", $resource, 3);
            $localeModel = new EmCore_Model_Locale();
            $resource = $localeModel->find($split[2]);
        } else if(preg_match("/^Emerald_Activity/", $resource)) {
            $split = explode("_", $resource, 3);
            $activityModel = new EmAdmin_Model_Activity();
            $splitted = explode("___", $split[2]);
            $resource = $activityModel->findByCategoryAndName($splitted[0], $splitted[1]);
        }

        return $resource;
    }
    
    
    
    
    
}