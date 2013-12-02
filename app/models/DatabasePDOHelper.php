<?php

class DatabasePDOHelper {

    //The database connection:
    private $_dbConnection = false;
    
    //A static member variable representing the class instance
    private static $_instance = null;
    
    private function __construct() {
        //Prevent the class from being instantiated. So that there is only one instance, the one created by the class itself.
    }

    public function __clone() {
        //trigger_error( "Cannot clone instance of Singleton pattern ...", E_USER_ERROR );
    }

    public function __wakeup() {
        //trigger_error('Cannot deserialize instance of Singleton pattern ...', E_USER_ERROR );
    }

    //Have a single globally accessible static method
    public static function getInstance() {
        logger::debugStart();
        logger::debug("Checking if singleton exists...");
        
        if (self::$_instance === null) {
            logger::debug("Could not get instance of DatabasePDOHelper, creating instance...");

            try {
                self::$_instance = new DatabasePDOHelper();
                
            } catch (Exception $ex) {
                logger::error("Problem creating singleton: " . $ex);
            }
            
        } else {
            logger::debug("Found instance of DatabasePDOHelperSingleton, not creating again...");
        }

        logger::debugEnd();
        return self::$_instance;
    }
    
    public function getConnection() {
        logger::debugStart();

        if (!$this->_dbConnection)
        {
            try {
                logger::debug("Creating the database connection to " . config::$db_database . "@" . config::$db_hostname);

                $this->_dbConnection = new PDO("mysql:dbname=".config::$db_database.";host=".config::$db_hostname.";charset=utf8", config::$db_username, config::$db_password);
                
                //$this->_dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                //$this->_dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                /*
                if ($this->_dbConnection->errorCode()) {
                    logger::error("Failed to connect to MySQL: " . $this->_dbConnection->errorCode());
                } else {
                    logger::debug("Connected to MySQL...");
                }
                 */

                $this->_dbConnection->query("SET NAMES utf8");
                
            } catch (Exception $ex) {
                logger::error("Problem initialising database: " . $ex);
            }
        }
        else //Already connected
        {        
            logger::debug("Existing database connection found...");
        }
        
        return $this->_dbConnection;   
        
        logger::debugEnd();
    }

}

?>
