<?php
/**
* Manage User ACL
*/

class BuckysUserAcl
{
    static $USER_ACL = null;
    
    /**
    * Define User Acl Constants
    * It will be called on the bootstrap file
    * 
    */
    public function defineAclConstants()
    {
        if(BuckysUserAcl::$USER_ACL == null)
            BuckysUserAcl::loadAcl();
        
        foreach(BuckysUserAcl::$USER_ACL as $row)
        {
            if(!defined('USER_ACL_' . strtoupper($row['Name'])))
                define('USER_ACL_' . strtoupper($row['Name']), $row['Level']);
        }
        
    }
    
    /**
    * Get ACL data from database and store it to $USER_ACL
    * 
    */
    public function loadAcl()
    {
        global $db;
        
        $query = "SELECT * FROM " . TABLE_USER_ACL . " ORDER BY Level";
        $rows = $db->getResultsArray($query);
        
        BuckysUserAcl::$USER_ACL = $rows;
        
        return;
    }
    
    /**
    * Get id from level
    * 
    * @param Int $acl
    */
    public function getIdFromLevel($level)    
    {
        global $db;
        
        if(BuckysUserAcl::$USER_ACL == null)
            BuckysUserAcl::loadAcl();
        
        foreach(BuckysUserAcl::$USER_ACL as $row)
        {
            if($row['Level'] == $level)
                return $row['aclID'];
        }
        
    }
    
    /**
    * Get id from Name
    * 
    * @param Int $acl
    */
    public function getIdFromName($name)    
    {
        global $db;
        
        if(BuckysUserAcl::$USER_ACL == null)
            BuckysUserAcl::loadAcl();
        
        foreach(BuckysUserAcl::$USER_ACL as $row)
        {
            if(strtolower($row['Name']) == strtolower($name))
                return $row['aclID'];
        }
        
    }
    
    /**
    * Get level from id
    * 
    * @param Int $acl
    */
    public function getLevelFromId($ac_id)    
    {
        global $db;
        
        if(BuckysUserAcl::$USER_ACL == null)
            BuckysUserAcl::loadAcl();
        
        foreach(BuckysUserAcl::$USER_ACL as $row)
        {
            if($row['aclID'] == $ac_id)
                return $row['Level'];
        }
        
    }
    
    /**
    * Get Level from level
    * 
    * @param Int $acl
    */
    public function getLevelFromName($name)    
    {
        global $db;
        
        if(BuckysUserAcl::$USER_ACL == null)
            BuckysUserAcl::loadAcl();
        
        foreach(BuckysUserAcl::$USER_ACL as $row)
        {
            if(strtolower($row['Name']) == strtolower($name))
                return $row['Level'];
        }
        
    }
    
    /**
    * Get name from level
    * 
    * @param Int $acl
    */
    public function getNameFromLevel($level)    
    {
        global $db;
        
        if(BuckysUserAcl::$USER_ACL == null)
            BuckysUserAcl::loadAcl();
        
        foreach(BuckysUserAcl::$USER_ACL as $row)
        {
            if($row['Level'] == $level)
                return $row['Name'];
        }
        
    }
    
}