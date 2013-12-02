<?php

class AgangUser extends MxitUser {

    //Object fields:
    public $UserVotingPollResult;
    
    /**
     * User quiz game result
     *
     * @var UserHasQuizGameResult
     */
    public $UserQuizGameResult;
    
    /**
     * User details
     *
     * @var UserHasDetails
     */
    public $UserHasDetails;

    public function __construct() {
        logger::debugStart();
        
        //Call the MxitUser's construct method, which will call the checkUserExists_fromDB method and set the UserOID:
        logger::debug("Calling parent constructor method...");
        parent::__construct();
        
        logger::debug("AgangUser.MxitUserID: " . $this->getMxitUserId() . " UserOID:" . $this->UserOID);    
        
        logger::debugEnd();
    }    

}