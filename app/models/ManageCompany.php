<?php

class ManageCompany
{
    /**
     * @desc Add a new company into the database.
     * @param type $Company Company object with all company information to be created
     * @return boolean TRUE if company added successfully, FALSE if company not added.
     */
    public function addCompany($Company)
    {
        
        $db = DatabasePDOHelper::getInstance()->getConnection();
        
        try{
            
            $sql =
            'INSERT INTO '
                    .config::$tableNamePrefix.'company
               (
                Name,
                Description               
               )
               VALUES
               (
                :Name,
                :Description               
               );';
        
        logger::sql($sql);
        logger::debug('Preparing sql statement..');
        
        $statement = $db->prepare($sql);
        
        //Bind the parameters for the query:
        if ($statement){
           logger::debug('binding paramenters..');
           //bidnig goes here.
           
           $statement->bindValue(":Name", $Company->getName(),  PDO::PARAM_STR);
           $statement->bindValue(":Description", $Company->getDescription(),  PDO::PARAM_STR);
        }else{                      
           logger::error('Preparing PDO statement failed!');
           return false;
        }
        
        //Execute the statement:
        $executeSuccess = $statement->execute();
        
            if ($executeSuccess && $statement->rowCount() > 0){
                //logger::debug("Inserted row to DB...");
                
                $//insertedUserOID = $db->lastInsertId();               
                
                //logger::debug("Setting this user's userOID to:" . $insertedUserOID);
                //$this->UserOID = $insertedUserOID;                
                $success = true;
                
            }else{
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                $success = false;
            }
        
        }catch (Exception $ex){
            logger::error("Problem persisting user info: " . $ex);
        }
        
        logger::debugEnd();
        return $success;
        
    }
    
    /**
     * @desc Update Company information in the databse.
     * @param type $companyOID
     * @param type $values Associative array with values to be updated.
     *        Key = Company object field, Value is the new updated value for the field.
     * @return boolean TRUE if company successfully updated, FALSE if company not updated.
     */
    public function updateComapany($companyOID,$values)
    {
        
    }
    
    /**
     * @desc Deletes a company record from the database(mark the record as deleted.)
     * @param type $companyOID ID of the company to be deleted.
     * @return boolean TRUE successfully deleted, FALSE is company not deleted.
     */
    public function deleteCompany($companyOID)
    {
        
    }
    
    /**
     * @desc Get information of the company with the specified ID.
     * @param type $companyOID ID of the company to look up information for.
     * @return mixed Company object if company ID is valid, FALSE if no information found.
     */
    public function getCompany($companyOID)
    {
        
    }
    
    /**
     * @desc Get information for all companies registered in the database.
     * @return mixed Array of Company object if information is found, 
     *         FALSE if no information was found.
     */
    function getAllCompanies()
    {
        logger::debugStart();

        $success = false;

        //Wrap all database code in a try/catch block. This will catch any sql exceptions.
        try {
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            
            // get budget categories set for this user from DB:
            $sql = "
            SELECT 
                Company_OID,
                Name, 
                Description
            FROM 
                " . config::$tableNamePrefix . "company
            ;
            ";

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
                
                $Companies = array();
                
                foreach ($result as $company)
                {
                    $newCompany = new Company();
                    $newCompany->setCompanyOID($company['Company_OID']);
                    $newCompany->setName($company['Name']);
                    $newCompany->setDescription($company['Description']);
                    
                    array_push($Companies, $newCompany);
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
        return $Companies;
    }
}