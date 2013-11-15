<?php

class ManageUser{
    
    /**
     * @desc Add the user into the database
     * @param type $user MxitUser object with all user information.
     * @return boolen Returns TRUE if user successfully created, else returns FALSE
     */
    public function addUser($user)
    {
        $db = DatabasePDOHelper::getInstance()->getConnection();
        
        try{
            
            $sql =
            'INSERT INTO '
                    .config::$tableNamePrefix.'user
              ()
              VALUES
              ()';
        
        //logger::sql($sql);
        //logger::debug('Preparing sql statement..');
        
        $statement = $db->prepare($sql);
        
        //Bind the parameters for the query:
        if ($statement){
           //logger::debug('binding paramenters..');
           //bidnig goes here.
        }else{                      
           logger::error('Preparing PDO statement failed!');
           return false;
        }
        
        //Execute the statement:
        $executeSuccess = $statement->execute();
        
            if ($executeSuccess && $statement->rowCount() > 0){
                //logger::debug("Inserted row to DB...");
                
                $insertedUserOID = $db->lastInsertId();               
                
                //logger::debug("Setting this user's userOID to:" . $insertedUserOID);
                $this->UserOID = $insertedUserOID;                
                $success = true; 
            }else{
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                $success = false;
            }
        
        }catch (Exception $ex){
            logger::error("Problem persisting user info: " . $ex);
        }
        
        //logger::debugEnd();
        return $success;
        
    }//end addUser
    
    /**
     * 
     * @param type $userOID
     * @param type $options Associative array with values to be updated.
     *             Key = MxitUser object field, Value = New Value.
     */
    public function updateUser($userOID,$options)
    {
        
    }
    
    /**
     * @desc Deletes a user from the database(make the record as deleted.)
     * @param type $userOID User ID for the user to be deleted
     * @return boolean TRUE if user successfully deleted, else FALSE. 
     */
    public function deleteUser($userOID)
    {
        
    }
    
    /**
     * @desc Get user information from database.
     * @param type $User_OID ID of user to get information for.
     * @return mixed MxitUser object if user ID was found in he databse,
     *         FALSE if user ID is not in the databse.
     */
    public function getUserInfo($User_OID)
    {
        
    }


    /**
     * @desc Get information about all users
     * @return mixed Array of user object if information found, 
     *         FALSE if no user information was found
     */
    public function getAllUsers()
    {
        
    }
    
    /**
     * @desc Get trades created by the specified user.
     * @param type $userID ID of the user to get trades for.
     */
    public function getUserTrades($user_OID)
    {
        //logger::debugStart();

        $success = false;

        //Wrap all database code in a try/catch block. This will catch any sql exceptions.
        try {
            //logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            
            // get budget categories set for this user from DB:
            $sql = "
            SELECT 
                Trade_OID,
                Name, 
                Description,
                User_OID,
                Date_Created,
                Time_Created,
                Status,
                Request_Category_OID,
                Offer_Category_OID,
                Request_Value,
                Offer_Value
                
            FROM 
                " . config::$tableNamePrefix . "trade
            WHERE User_OID='".$user_OID."'
            ;
            ";

            //logger::sql($sql);
            $statement = $db->prepare($sql);
            
            if ($statement){
                logger::debug('Bind variable to place holder..');           
                
            }  else {
                logger::error('Preparing PDO statement failed! ');
                return false;
            }

            //logger::debug('Executing SQL statement...');
            $executeSuccess = $statement->execute();
            
            if ($executeSuccess){
                //logger::debug('load read data into array');
                $result = $statement->fetchAll();
                
                $Trades = array();
                
                foreach ($result as $trade)
                {
                    $newTrade = new Trade();
                    
                    $newTrade->setTradeOID($trade['Trade_OID']);
                    $newTrade->setUserOID($trade['User_OID']);
                    $newTrade->setTradeName($trade['Name']);
                    $newTrade->setDescription($trade['Description']);
                    $newTrade->setStutus($trade['Status']);
                    $newTrade->setTimeCreated($trade['Time_Created']);
                    $newTrade->setDateCreated($trade['Date_Created']);
                    $newTrade->setRequestCategoryOID($trade['Request_Category_OID']);
                    $newTrade->setOfferCategoryOID($trade['Offer_Category_OID']);
                    $newTrade->setRequestValue($trade['Request_Value']);
                    $newTrade->setOfferValue($trade['Offer_Value']);
                    
                    array_push($Trades, $newTrade);
                }
                
            }else{
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }            
        } catch (Exception $ex) {
            logger::error("Problem reading user info: " . $ex);
        }

        //logger::debugEnd();
        return $Trades;
    }
    
    /**
     * @desc Create a bid offer for a trade.
     * @param type $UserID User ID of the user bidding for the trade.
     * @param type $TradeID Trade which a user is bidding for.
     * @param type $offer The value a user is willing to trade for Money/Clocks
     * @return boolean TREU if bid was made successfully, else FALSE
     */
    public function make_a_bid($UserID,$Trade_OID,$offer)
    {
        $db = DatabasePDOHelper::getInstance()->getConnection();
        
        try{
            
            $sql =
            'INSERT INTO '
                    .config::$tableNamePrefix.'bid
             (
              Bid_User_OID,
              Trade_OID,
              Trade_User_OID, 
              Bid_Date, 
              Bid_Time, 
              Trade_Request_Category, 
              Trade_Request_Offer, 
              Bid_Status
              )              
              VALUES
             (
              :Bid_User_OID, 
              :Trade_OID,
              :Trade_User_OID, 
              :Bid_Date, 
              :Bid_Time, 
              :Trade_Request_Category, 
              :Trade_Request_Offer, 
              :Bid_Status
              )';
        
        //logger::sql($sql);
        //logger::debug('Preparing sql statement..');
        
        $statement = $db->prepare($sql);
        $BidDate = date('Y-m-d');
        $BidTime = date('H:s:i');
        $BidStatus = 2;
        $TradeRquest = 2;
        $Trade_User_OID = 2;        
        
        //Bind the parameters for the query:
        if ($statement){
           //logger::debug('binding paramenters..');
           
           $statement->bindValue(":Bid_User_OID", $UserID,  PDO::PARAM_STR);
           $statement->bindValue(":Trade_OID",$Trade_OID ,  PDO::PARAM_STR);
           $statement->bindValue(":Trade_User_OID",$Trade_User_OID ,  PDO::PARAM_STR);
           $statement->bindValue(":Bid_Date", $BidDate,  PDO::PARAM_STR);
           $statement->bindValue(":Bid_Time", $BidTime,  PDO::PARAM_STR);
           $statement->bindValue(":Trade_Request_Category", $TradeRquest,  PDO::PARAM_STR);
           $statement->bindValue(":Trade_Request_Offer", $$offer,  PDO::PARAM_STR);
           $statement->bindValue(":Bid_Status", $BidStatus,  PDO::PARAM_STR);
           
        }else{                      
           logger::error('Preparing PDO statement failed!');
           return false;
        }
        
        //Execute the statement:
        $executeSuccess = $statement->execute();
        
            if ($executeSuccess && $statement->rowCount() > 0){
                //logger::debug("Inserted row to DB...");
                
                $insertedUserOID = $db->lastInsertId();               
                
                //logger::debug("Setting this user's userOID to:" . $insertedUserOID);
                $this->UserOID = $insertedUserOID;                
                $success = true; 
            }else{
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                $success = false;
            }
        
        }catch (Exception $ex){
            logger::error("Problem persisting user info: " . $ex);
        }
        
        //logger::debugEnd();
        return $success;
    }
}