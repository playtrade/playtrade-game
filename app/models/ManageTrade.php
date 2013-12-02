<?php

class ManageTrade{
    
    /**
     * @desc Persist information about a trade in a databse.
     * @param type $trade Trade Object with all information about the trade.
     * @param type $user Creator/Owner of the trade
     * @return boolean TRUE if added, else FALSE.
     */
    public function addTrade($trade,$userOID)
    {
        $db = DatabasePDOHelper::getInstance()->getConnection();
        
        try{
            
            $sql =
            'INSERT INTO '
                    .config::$tableNamePrefix.'trade
              (
                  Name,
                  Description,
                  Trade_OID,
                  User_OID,                   
                  Date_Created, 
                  Time_Created, 
                  Request_Category_OID, 
                  Offer_Category_OID, 
                  Status,
                  Request_Value,
                  Offer_Value
              )
                
              VALUES
              (
                  :Name,
                  :Description,
                  :Trade_OID,
                  :User_OID,                   
                  :Date_Created, 
                  :Time_Created, 
                  :Request_Category_OID, 
                  :Offer_Category_OID, 
                  :Status,
                  :Request_Value,
                  :Offer_Value
              );';
        
        logger::sql($sql);
        logger::debug('Preparing sql statement..');
        
        $statement = $db->prepare($sql);
        
        //Bind the parameters for the query:
        if ($statement){
           logger::debug('binding paramenters..');
           //bidnig goes here.
           
           $statement->bindValue(":Name", $trade->getTradeName(),  PDO::PARAM_STR);
           $statement->bindValue(":Description", $trade->getDescription(),  PDO::PARAM_STR);
           $statement->bindValue(":User_OID", $trade->getUserOID(),  PDO::PARAM_STR);
           $statement->bindValue(":Date_Created", $trade->getDateCreated(),  PDO::PARAM_STR);
           $statement->bindValue(":Time_Created", $trade->getTimeCreated(),  PDO::PARAM_STR);
           $statement->bindValue(":Status", $trade->getStatus(),  PDO::PARAM_STR);
           $statement->bindValue(":Trade_Request_Category", $trade->getRequestCategoryOID(),  PDO::PARAM_STR);
           $statement->bindValue(":Trade_Offer_Category", $trade->getOfferCategoryOID,  PDO::PARAM_STR);
           $statement->bindValue(":Request_Value", $trade->getRequestValue(),  PDO::PARAM_STR);
           $statement->bindValue(":Offer_Value", $trade->getOfferValue(),  PDO::PARAM_STR);
             
        }else{                      
           logger::error('Preparing PDO statement failed!');
           return false;
        }
        
        //Execute the statement:
        $executeSuccess = $statement->execute();
        
            if ($executeSuccess && $statement->rowCount() > 0){
                logger::debug("Inserted row to DB...");
                
                $insertedUserOID = $db->lastInsertId();               
                
                logger::debug("Setting this user's userOID to:" . $insertedUserOID);
                //$this->UserOID = $insertedUserOID;                
                $success = true; 
            }else{
                $errorArray = $statement->errorInfo();
                //logger::error("Error executing prepared statement: " . $errorArray[2]);
                $success = false;
            }
        
        }catch (Exception $ex){
        logger::error("Problem persisting user info: " . $ex);
        }
        
        //logger::debugEnd();
        return $success;
    }

    /**
     * @desc Updates trade information in the databse.
     * @param type $tradeOID Trade object with information about trade trade to be updated.
     * @param type $options Associative array with values to be updates.
     *        Key = Field and Value = Table value.
     * @return boolean Returns TRUE if the record was successfullly updated from  the database.
     *         Else return FALSE
     */
    public function updateTrade($tradeOID,$options)
    {
        
    }
    
    /**
     * @desc Delete the trade from the database.(Make the record as deleted.)
     * @param type $trade Trade object with information about the trade to be deleted.
     * @return boolean Returns TRUE if the record was successfullly deleted from  the database.
     *         Else return FALSE
     */
    public function deleteTrade($trade)
    {
        
    }
    
    /**
     * @desc Returns information about a trade
     * @param type $tradeId ID of trade to get information for
     * @return mixed Returns trade information of available, else returns FALSE.
     */
    public function getTrade($Trade_OID)
    {
        //logger::debugStart();

        $success = false;

        //Wrap all database code in a try/catch block. This will catch any sql exceptions.
        try {
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            
            // get budget categories set for this user from DB:
            $sql = "
            SELECT     
                  Name,
                  Description,
                  Trade_OID,
                  User_OID,                   
                  Date_Created, 
                  Time_Created, 
                  Request_Category_OID, 
                  Offer_Category_OID, 
                  Status,
                  Request_Value,
                  Offer_Value
            FROM 
                " . config::$tableNamePrefix . "trade
            WHERE Trade_OID=".$Trade_OID;

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
                
                    $trade = $statement->fetchAll(); 
                    $trade = $trade[0];                 
                    $newTrade = new Trade();
                    
                    $newTrade->setTradeOID($trade['Trade_OID']);
                    $newTrade->setTradeName($trade['Name']);
                    $newTrade->setUserOID($trade['User_OID']);
                    $newTrade->setDescription($trade['Description']);
                    $newTrade->setStutus($trade['Status']);
                    $newTrade->setTimeCreated($trade['Time_Created']);
                    $newTrade->setDateCreated($trade['Date_Created']);
                    $newTrade->setRequestCategoryOID($trade['Request_Category_OID']);  
                    $newTrade->setOfferCategoryOID($trade['Offer_Category_OID']);
                    $newTrade->setRequestValue($trade['Request_Value']);
                    $newTrade->setOfferValue($trade['Offer_Value']);
                
            }else{
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }            
        } catch (Exception $ex) {
            logger::error("Problem reading user info: " . $ex);
        }

        //logger::debugEnd();
        return $newTrade;
    }
    
    /**
     * @desc Get all the posted trades by users
     * @return mixed Return an array with all trades as objects if there are trades,
     *         else return FALSE.
     */
    public function getAllTrades()
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
                Name,
                  Description,
                  Trade_OID,
                  User_OID,                   
                  Date_Created, 
                  Time_Created, 
                  Request_Category_OID, 
                  Offer_Category_OID, 
                  Status,
                  Request_Value,
                  Offer_Value
            FROM 
                " . config::$tableNamePrefix . "trade
            ;
            ";

            //logger::sql($sql);
            $statement = $db->prepare($sql);
            
            if ($statement){
                //logger::debug('Bind variable to place holder..');           
                
            }  else {
                //logger::error('Preparing PDO statement failed! ');
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
                    $newTrade->setTradeName($trade['Name']);
                    $newTrade->setDescription($trade['Description']);
                    $newTrade->setTimeCreated($trade['Time_Created']);
                    $newTrade->setDateCreated($trade['Date_Created']);
                    $newTrade->setStutus($trade['Status']);
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
     * Gets the number of bids for a trade.
     * @param type $Trade_OID
     * @return mixed Number of bids if successfull, FALSE if an error occured.
     */
    function getNumberOfTradeBids($Trade_OID)
    {
        $success = false;

        //Wrap all database code in a try/catch block. This will catch any sql exceptions.
        try {
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            
            // get budget categories set for this user from DB:
            $sql = "
            SELECT 
                COUNT(*)
            FROM 
                " . config::$tableNamePrefix . "bid
            WHERE Trade_OID=".$Trade_OID;

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
                
                   $Bid_count = $statement->fetchAll(); 
                   $Bid_count = $Bid_count[0];                               
                   $Bids = (int) $Bid_count[0];     
              
                
            }else{
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }            
        } catch (Exception $ex) {
            logger::error("Problem reading user info: " . $ex);
        }

        //logger::debugEnd();
        return $Bids;
    }
    
}