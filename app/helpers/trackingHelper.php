<?php

class trackingHelper {

    static public function trackGoogleAnalytics($pageTitle, $url = false) {

        //Get the user from the PHP session:
        /* @var $User MyBudgetUser */
        $User = $_SESSION['user'];
        
        // Initilize GA Tracker
        logger::debug("Creating Google Analytics tracker:" . config::$googleTracking_Code . "," . config::$googleTracking_Domain);
        $tracker = new UnitedPrototype\GoogleAnalytics\Tracker(config::$googleTracking_Code, config::$googleTracking_Domain);
        
        //Add gender custom variable:
        $customVarilableIndex = 1; //Position 1 to 5 of custom variable
        $customVarilableName = "Gender"; 
        $customVarilableValue = ($User->genderCode == 0) ? 'Female' : 'Male';
        $customVarilableScope = 1; //Visitor scope
        $customVariable1 = new UnitedPrototype\GoogleAnalytics\CustomVariable($customVarilableIndex, $customVarilableName, $customVarilableValue, $customVarilableScope);
        $tracker->addCustomVariable($customVariable1);

        //Add YearOfBirth custom variable:
        $customVarilableIndex = 2; //Position 1 to 5 of custom variable
        $customVarilableName = "YearOfBirth"; 
        $customVarilableValue = $User->dateOfBirth->format('Y');
        $customVarilableScope = 1; //Visitor scope
        $customVariable2 = new UnitedPrototype\GoogleAnalytics\CustomVariable($customVarilableIndex, $customVarilableName, $customVarilableValue, $customVarilableScope);
        $tracker->addCustomVariable($customVariable2);     
        
        //Add UserAgent custom variable:
        $customVarilableIndex = 3; //Position 1 to 5 of custom variable
        $customVarilableName = "ScreenSize"; 
        $customVarilableValue = $User->deviceWidth . "x" . $User->deviceHeight;
        $customVarilableScope = 2; //session scope https://developers.google.com/analytics/devguides/collection/gajs/gaTrackingCustomVariables
        $customVariable3 = new UnitedPrototype\GoogleAnalytics\CustomVariable($customVarilableIndex, $customVarilableName, $customVarilableValue, $customVarilableScope);
        $tracker->addCustomVariable($customVariable3);           
        
        // Assemble Page information
        if (!$url) {
            $url = "/" . substr($_SERVER['PHP_SELF'], 25);
        }

        $page = new UnitedPrototype\GoogleAnalytics\Page($url);
        $page->setTitle($pageTitle);

        // Update GA Session information
        logger::debug("Get Google Analytics session from User object...");
        if (isset($User->GoogleAnalyticsSession)) {
            $ga_session = $User->GoogleAnalyticsSession;
        } else {
            logger::error("Google analytics session wasn't set in user object...");
            $ga_session = new UnitedPrototype\GoogleAnalytics\Session();
        }
        
        // Track page view            
        $tracker->trackPageview($page, $ga_session, $User->GoogleAnalyticsVisitor);

        logger::debug("Updating user page visit stats to db...");
        //$User->update_User_PageStats_toDB();

        $_SESSION['user'] = $User;
    }

}

?>