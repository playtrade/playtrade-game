<?php

/**
 * Configuration values that apply to all environments.
 *
 * @author Eric Clements
 */
class configDefault {

//===============================================
// Layout
//===============================================    
    public static $isShowLogoBanner = false;     
    public static $isShowCopyrightFooter = false;
    public static $copyrightText = "Kazazoom (Pty) Ltd";

//===============================================
// Database
//===============================================    
    public static $tableNamePrefix = "playtrade_";    

//===============================================
// Methods
//===============================================    

    public static function getEnvironment() {
        return getenv('ENVIRONMENT');
    }
    
//===============================================
// Memcached
//===============================================        
    
    public static $appContactName='mxorbdev';        
    public static $memcachedHost='unxdev02.kazazoom.com';    
    public static $memcachedPort='11211'; 
    public static $VotingPollCache_LifeTime = 3600; // 1 hours
    public static $TipCache_LifeTime = 3600; //1 hours
    public static $QuizGameCache_LifeTime = 3600; //1 hours
    public static $FormCache_LifeTime = 3600; // 1 hour
}

?>
