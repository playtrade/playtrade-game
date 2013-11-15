<?php

date_default_timezone_set("Africa/Johannesburg");

//===============================================
// mod_rewrite
//===============================================
//Please configure via .htaccess or httpd.conf
//===============================================
// Madatory KISSMVC Settings (please configure)
//===============================================
define('APP_PATH', 'app/'); //with trailing slash pls
define('WEB_FOLDER', '/playtradedemo/'); //with trailing slash pls
//===============================================
// Other Settings
//===============================================
$GLOBALS['sitename'] = 'Playtradedemo';

//===============================================
// Includes
//===============================================
require('kissmvc.php');
require_once APP_PATH . 'inc/functions.php';
require_once('subscription_api.php');

// Check if it's been set by the web server
$environment = getenv('ENVIRONMENT');
if (!$environment) {
    $environment = "development";
}

// Load in default configuration values
require_once APP_PATH . 'config/' . 'config.default.php';

// Load in the overridden configuration file for this environment
require_once APP_PATH . 'config/' . 'config.' . $environment . '.php';

//===============================================
// Debug
//===============================================
ini_set('display_errors', 'On');
if (config::$isShowDebug || config::$isShowError) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

//===============================================
// Uncaught Exception Handling
//===============================================s
/*
  set_exception_handler('uncaught_exception_handler');

  function uncaught_exception_handler($e) {
  ob_end_clean(); //dump out remaining buffered text
  $vars['message']=$e;
  die(View::do_fetch(APP_PATH.'errors/exception_uncaught.php',$vars));
  }

  function custom_error($msg='') {
  $vars['msg']=$msg;
  die(View::do_fetch(APP_PATH.'errors/custom_error.php',$vars));
  }
 */

//===============================================
// Autoloading for Business Classes
//===============================================
// Assumes Model Classes start with capital letters and Helpers start with lower case letters

function __autoload($classname) {
    $a = $classname[0];

    $needle = 'GoogleAnalytics';
    $isGoogleAnalyticsClass = (strpos($classname, $needle) !== false);

    if ($isGoogleAnalyticsClass) {

        $classPath = strtr($classname, '\\', '/');

        //$fullClassFilename = APP_PATH . 'inc/UnitedPrototype/' . $classPath . '.php';
        $fullClassFilename = APP_PATH . 'inc/' . $classPath . '.php';

        //logger::error("Loading class: " . $fullClassFilename);

        require_once($fullClassFilename);
    } elseif ($a >= 'A' && $a <= 'Z') {

        //logger::error("Loading model: " . $classname);
        //models start with caps
        include_once(APP_PATH . 'models/' . $classname . '.php');
    } else {

        //logger::error("Loading helper: " . $classname);
        //helper classes start with small caps:
        require_once(APP_PATH . 'helpers/' . $classname . '.php');
    }
}

//===============================================
// Session
//===============================================

include_once APP_PATH . 'inc/sessionInitialise.php'; 

//===============================================
// Start the controller
//===============================================

$controller = new Controller(APP_PATH . 'controllers/', WEB_FOLDER, 'Main', 'index');

?> 