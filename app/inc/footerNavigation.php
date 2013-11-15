<?php

/**
 * Displays footer navigation links to allow back and home navigation
 */
function footerNav()
{
    $back = $_SERVER['HTTP_REFERER']; // get the url that the user came from use it as back link
    $home = APP_PATH . 'views/Main/LandingMxitView.php'; // get the root page
    
    echo "<a href=$back>Back</a> | <a href=$home>Home</a>"; // display the links
}