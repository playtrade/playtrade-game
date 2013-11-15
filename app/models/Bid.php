<?php

class Bid
{
    private $Bid_OID;
    private $Trade_OID;
    private $Bid_User_OID; /* The user making a trade bid. */
    private $Trade_User_OID; /* The owner/creator of the bid */
    private $Bid_Date;
    private $Bid_Time;
    private $Trade_Rquest_Category; /* The category for which the owner want to trade for */
    private $Trade_Offer_Category; /* The offer */
    private $Trade_Rquest; /* The value that the bid owner is willing to trade for */
    private $Trade_Offer; /* The value that the bidding user is offering */
    private $Bid_Status; /* The status of the bid, accepted, pending, or declined */
    
    public function getBid_OID() {
        return $this->Bid_OID;
    }

    public function getBid_User() {
        return $this->Bid_User;
    }

    public function getTrade_User() {
        return $this->Trade_User;
    }

    public function getBid_Date() {
        return $this->Bid_Date;
    }

    public function getBid_Time() {
        return $this->Bid_Time;
    }

    public function getTrade_Rquest() {
        return $this->Trade_Rquest;
    }

    public function getTrade_Offer() {
        return $this->Trade_Offer;
    }

    public function getBid_Status() {
        return $this->Bid_Status;
    }

    public function setBid_OID($Bid_OID) {
        $this->Bid_OID = $Bid_OID;
    }

    public function setBid_User($Bid_User) {
        $this->Bid_User = $Bid_User;
    }
    
    public function setTrade_OID($Trade_OID){
        $this->Trade_OID = $Trade_OID;
    }

    public function setTrade_User($Trade_User) {
        $this->Trade_User = $Trade_User;
    }

    public function setBid_Date($Bid_Date) {
        $this->Bid_Date = $Bid_Date;
    }

    public function setBid_Time($Bid_Time) {
        $this->Bid_Time = $Bid_Time;
    }

    public function setTrade_Rquest($Trade_Rquest) {
        $this->Trade_Rquest = $Trade_Rquest;
    }

    public function setTrade_Offer($Trade_Offer) {
        $this->Trade_Offer = $Trade_Offer;
    }

    public function setBid_Status($Bid_Status) {
        $this->Bid_Status = $Bid_Status;
    }


}