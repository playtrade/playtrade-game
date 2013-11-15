<?php

/**
 * Configuration values that apply to all environments.
 *
 * @author Eric Clements
 */
class config extends configDefault {

//===============================================
// Layout
//===============================================      
    public static $isShowCopyrightFooter = true;    
    public static $isShowLogoBanner = true;     
    
//===============================================
// Logging
//===============================================    
    public static $isShowDebug = false;
    public static $isShowError = true;
    public static $isShowSQL = false;
    
//===============================================
// Caching
//===============================================
    public static $isUsePHPSession = true;
    public static $isUseMemcache = true;

//===============================================
// Database
//===============================================
    public static $db_hostname = 'unxdev02.kazazoom.com';
    public static $db_username = 'themba';
    public static $db_password = '123tiger';
    public static $db_database = 'playtradedb_stg';

//===============================================
// Copy
//===============================================        
    public static $appNameString = 'Playtrade Development App';

//===============================================
// Tracking
//===============================================        
    public static $googleTracking_Code = 'MO-39595150-2';
    public static $googleTracking_Domain = 'kazazoom.com';          
    
}

?>
