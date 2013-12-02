<?php

class Trade{
    
    private $TradeOID;
    private $TradeName;
    private $Description;
    private $UserOID;
    private $DateCreated;
    private $TimeCreated;
    private $Stutus;
    private $RequestCategoryOID;
    private $OfferCategoryOID; //The category for which the user wish to trade their item.
    private $RequestValue;
    private $OfferValue;
    private $bids;
    
    
    
    public function getNumberOfBids()
    {
        
        $Bids = $this->getTradeBids();
        $NumberOfBids = count($Bids);
        echo '<pre>';
        print_r($Bids);
        echo '</pre>';
        return $NumberOfBids;
        
    }

        /**
     * Return an array of all the bids for the trade.
     */
    public function getTradeBids()
    {      
        $success = false;

        //Wrap all database code in a try/catch block. This will catch any sql exceptions.
        try {
            //logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            
            // get budget categories set for this user from DB:
            $sql = "
            SELECT 
                  Bid_User_OID,
                  Trade_OID,
                  Trade_User_OID, 
                  Bid_User_OID,
                  Bid_Date, 
                  Bid_Time, 
                  Trade_Request_Category, 
                  Trade_Offer_Category, 
                  Bid_Status,
                  Trade_Request_Value,
                  Trade_Offer_Value
            FROM 
                " . config::$tableNamePrefix . "bid
            WHERE Trade_OID=".$this->TradeOID;

           logger::sql($sql);
            $statement = $db->prepare($sql);
            
            if ($statement){
                logger::debug('Bind variable to place holder..');           
                
            }  else {
                logger::error('Preparing PDO statement failed! ');
                return false;
            }

            logger::debug('Executing SQL statement...');
            $executeSuccess = $statement->execute();
            
            if ($executeSuccess){
                logger::debug('load read data into array');
                
                $result = $statement->fetchAll();
                
                $Bids = array();
                
                echo '<pre>';
                print_r($result);
                echo '</pre>';
                
                
                foreach ($result as $bid)
                {
                    $newBid = new Bid();
                    
                    $newBid->setTrade_OID($bid['Trade_OID']);
                    $newBid->setBid_User($bid['Bid_User_OID']);
                    $newBid->setBid_Time($bid['Bid_Time']);
                    $newBid->setBid_Date($bid['Bid_Date']);
                    $newBid->setTrade_Offer($bid['Trade_Request_Offer']);
                    $newBid->setTrade_Rquest($bid['Trade_Request_Category']);
                    $newBid->setTrade_User($bid['Trade_User_OID']);
                    $newBid->setBid_Status($bid['Bid_Status']);
                    
                    array_push($Bids, $newBid);
                }    
              
                
            }else{
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }            
        } catch (Exception $ex) {
            logger::error("Problem reading user info: " . $ex);
        }
        
        logger::debugEnd();
        return $Bids;

    }
    
    public function getTradeOID() {
        return $this->TradeOID;
    }

    public function getTradeName() {
        return $this->TradeName;
    }
    
    public function getDescription() {
        return $this->Description;
    }

    public function getUserOID() {
        return $this->UserOID;
    }

    public function getDateCreated() {
        return $this->DateCreated;
    }

    public function getTimeCreated(){
        return $this->TimeCreated;
    }
    
    public function getStatus() {
        return $this->Stutus;
    }

    public function getRequestCategoryOID() {
        return $this->RequestCategoryOID;
    }
    
    public function getOfferCategoryID(){
        return $this->OfferCategoryOID;
    }            
    
    public function getRequestValue(){
        return $this->RequestValue;
    }
    
    public function getOfferValue(){
        return $this->OfferValue;
    }

    public function setTradeOID($TradeOID) {
        $this->TradeOID = $TradeOID;
    }
    
    public function setTradeName($TradeName) {
        $this->TradeName = $TradeName;
    }

    public function setDescription($Description) {
        $this->Description = $Description;
    }

    public function setUserOID($UserOID) {
        $this->UserOID = $UserOID;
    }

    public function setDateCreated($DateCreated) {
        $this->DateCreated = $DateCreated;
    }

    public function setStutus($Stutus) {
        $this->Stutus = $Stutus;
    }

    public function setRequestCategoryOID($RequestCategoryOID) {
        $this->RequestCategoryOID = $RequestCategoryOID;
    }
    
    public function setOfferCategoryOID($OfferCategoryOID){
        $this->OfferCategoryOID = $OfferCategoryOID;
    }
    
    public function setRequestValue($RequestValue){
        $this->RequestValue  = $RequestValue;
    }
    
    public function setOfferValue($OfferValue){
        $this->OfferValue = $OfferValue;
    }

    public function setTimeCreated($time){
        $this->TimeCreated = $time;
    }
   
}
