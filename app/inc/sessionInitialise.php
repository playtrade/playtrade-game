<?php

session_start();
global $_SESSION;
$isSessionExists = (isset($_SESSION['user']));

if (!$isSessionExists || !config::$isUsePHPSession) {
    logger::debug("Calling session initialize...");
    
    //Create the user object. The constructor will read and setup required objects inside User:
    $user = new AgangUser(); 

    //Not sure why we need to do this, need to check with Andre if needed:
    $MxitUserId = $user->getMxitUserId();
    $is_mxitUserId_exists = ($MxitUserId != "");
    
    if ($is_mxitUserId_exists) {
        logger::debug("MxitUser is populated...");
        
        logger::debug("Adding user object to PHP session...");
        $_SESSION['user'] = $user;
        
    } else {
        logger::error("Could not find Mxit user. Halting.");
        exit;
    }
} else {
    logger::debug("Found existing session for user...");
    
    //var_dump('$_SESSION',$_SESSION);
}
?>