<?php

function _index($msg = 'controllers/Main/index') {

    //logger::debug("Checking what hanlde method to call...");

    switch (true) {
        case (true): handleLandingPage();
            break;
    }
}

function handleLandingPage() {
    //logger::debugStart();

    //rating defaults
    $_SESSION['menRating'] = 0;
    $_SESSION['womenRating'] = 0;
    $_SESSION['boysRating'] = 0;
    $_SESSION['girlsRating'] = 0;
    
    //logger::debug("Handling landing page...");
    $User = $_SESSION['user'];        
    
    //Select the view
    $view = new View(APP_PATH . 'views/Main/LandingMxitView.php',array('User'=>$User));

    $view->dump();

    //logger::debugEnd();
}
