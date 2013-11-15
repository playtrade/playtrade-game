<?php

class MxitUser {

    public $UserOID;
    public $mxitUserId;
    public $displayName;
    //X-Mxit-Location
    public $currentCountryCode;
    public $currentRegionCode;
    public $currentCityCode;
    public $currentCityName;
    public $currentMNOCode;
    public $currentMNOCellID;
    //X-Mxit-Profile
    public $languageCode;
    public $registeredCountry;
    public $dateOfBirth; //DateTime object
    public $genderCode;
    public $mxitTarrifPlan;
    public $deviceUserAgent;
    public $deviceIP;
    public $mobileNumber;
    public $deviceFeature;
    public $deviceWidth;
    public $deviceHeight;
    //Visit Stats
    public $dateCurrentAccess; //DateTime object
    public $datePreviousAccess; //DateTime object
    public $dateRegistered; //DateTime object
    public $visitCount;
    public $GoogleAnalyticsSession; //for ga-php script    
    public $GoogleAnalyticsVisitor; //for ga-php script    

    public function __construct() {
        logger::debugStart();

        //Set all fields from Mxit headers:
        logger::debug("Constructing the MxitUser object from Http Headers...");
        $this->constructFromHTTPHeaders();

        //Set defaults of parameters that will be passed to function by reference:
        $outIsUserExistsInDB = false;
        $outUserOID = -1;

        // Check if the user exists, if exists read user options from DB:
        logger::debug("Going to check if user exists in DB...");
        $isReadSuccess = $this->read_CheckUserExistsAndReadUserOID_FromDB($this->getMxitUserId(), $outIsUserExistsInDB, $outUserOID);

        // Assemble Visitor information
        logger::debug("Assemble Visitor information...");
        $this->GoogleAnalyticsVisitor = new UnitedPrototype\GoogleAnalytics\Visitor();
        $this->GoogleAnalyticsVisitor->setIpAddress($this->deviceIP);
        $this->GoogleAnalyticsVisitor->setUserAgent($this->deviceUserAgent);
        $this->GoogleAnalyticsVisitor->setScreenResolution($this->deviceWidth . "x" . $this->deviceHeight);
        $this->GoogleAnalyticsVisitor->setUniqueId($this->UserOID); //use UserOID as it is unique 

        if ($outIsUserExistsInDB) {
            logger::debug("Existing user found for user: " . $outUserOID . "=" . $this->UserOID);
            
            //Create a new visitor session for this user:
            $this->GoogleAnalyticsSession = new UnitedPrototype\GoogleAnalytics\Session();

            //Set the google visitor object from values from database:
            $this->GoogleAnalyticsVisitor->setVisitCount($this->visitCount);
            $this->GoogleAnalyticsVisitor->setCurrentVisitTime($this->dateCurrentAccess);
            $this->GoogleAnalyticsVisitor->setPreviousVisitTime($this->datePreviousAccess);
            $this->GoogleAnalyticsVisitor->setFirstVisitTime($this->dateRegistered);

            //Add this new visitor session to the visitor object:
            $this->GoogleAnalyticsVisitor->addSession($this->GoogleAnalyticsSession);
            
            $this->dateCurrentAccess = $this->GoogleAnalyticsVisitor->getCurrentVisitTime();
            $this->datePreviousAccess = $this->GoogleAnalyticsVisitor->getPreviousVisitTime();
            $this->visitCount = $this->GoogleAnalyticsVisitor->getVisitCount();            
            
            logger::debug("Updating user visit stats to db...");
            $this->update_User_VisitStats_toDB();
        
        } else {
            logger::debug("New user needs to be persisted...");

            //Create a new visitor session for this user:
            $this->GoogleAnalyticsSession = new UnitedPrototype\GoogleAnalytics\Session();            
            
            //Store the values from the Visitor Object in the User object so it will be persisted
            $this->dateCurrentAccess = $this->GoogleAnalyticsVisitor->getCurrentVisitTime();
            $this->datePreviousAccess = $this->GoogleAnalyticsVisitor->getPreviousVisitTime();
            $this->dateRegistered = $this->GoogleAnalyticsVisitor->getFirstVisitTime();
            $this->visitCount = (int) $this->GoogleAnalyticsVisitor->getVisitCount();

            $this->persist_User_toDB();

            logger::debug("Persisted new user - dateCurrentAccess: " . $this->dateCurrentAccess->format('Y/m/d H:i:s'));
            logger::debug("Persisted new user - datePreviousAccess: " . $this->datePreviousAccess->format('Y/m/d H:i:s'));
            logger::debug("Persisted new user - dateRegistered: " . $this->dateRegistered->format('Y/m/d H:i:s'));
            logger::debug("Persisted new user - visitCount: " . $this->visitCount);
        }
        
        logger::debug("Unique Google Tracking ID:" . $this->UserOID);

        //The above method will already have assigned UserOID to the value from the database.
        logger::debugEnd();
    }

    public function setUserOID($userOID) {
        $this->UserOID = (int) $userOID;
        return $this;
    }

    public function getUserOID() {
        return $this->UserOID;
    }

    public function setMxitUserId($mxitUserId) {
        $this->mxitUserId = (string) $mxitUserId;
    }

    public function getMxitUserId() {
        return $this->mxitUserId;
    }

    public function getDateOfBirth_mysql() {
        return $this->_dateOfBirth;
    }

    public function getDateOfBirth_unix() {
        return strtotime($this->_dateOfBirth);
    }

    public function getDeviceWidth() {
        return $this->deviceWidth;
    }

    public function getDeviceveHeight() {
        return $this->deviceHeight;
    }

    public function getDeviceUserAgent() {
        return $this->deviceUserAgent;
    }

    public function incrementVisitCount() {
        $this->visitCount += 1;
    }

    public function mapHTTPHeadersToUserArray() {
        logger::debugStart();
        foreach ($_SERVER as $h => $v)
            if (preg_match('/HTTP_(.+)/', $h, $hp))
                $headers[$hp[1]] = $v;

        logger::debugEnd();
        return $headers;
    }

    public function getAge() {
        logger::debugStart();

        $iTimestamp = strtotime($this->getDateOfBirth());
        $iAge = date('Y') - date('Y', $iTimestamp);

        $outAge = $iAge;

        if (date('n') < date('n', $iTimestamp)) {
            $outAge = --$iAge;
        } elseif (date('n') == date('n', $iTimestamp)) {
            if (date('j') < date('j', $iTimestamp)) {
                $outAge = $iAge - 1;
            } else {
                $outAge = $iAge;
            }
        } else {
            $outAge = $iAge;
        }

        logger::debugEnd();
        return $outAge;
    }

    public function constructFromHTTPHeaders() {
        logger::debugStart();


        //Map the HTTP headers into an array:
        $headersArray = $this->mapHTTPHeadersToUserArray();

        if (!isset($headersArray['X_MXIT_USERID_R'])) {

            //Mxit headers weren't provided lets default to a test user
            $MxitUserID = "m45582303003-test4";

            $headersArray['UA_PIXELS'] = "480x640";
            $headersArray['X_MXIT_LOCATION'] = "ZA,,06,,,Johannesburg,33138,5356074,";
            $headersArray['X_MXIT_PROFILE'] = "en,ZA,1996-01-01,Female,1";
            $headersArray['X_MXIT_USERID_R'] = "m45582303002";
            $headersArray['X_MXIT_NICK'] = "Rockstar 99";
            $headersArray['X_DEVICE_USER_AGENT'] = "Evo";
            $headersArray['X_FORWARDED_FOR'] = "192.168.1.1";

            logger::debug("Mxit headers NOT found! Defaulting user to TEST user: " . $headersArray['X_MXIT_USERID_R']);
        } else {
            $MxitUserID = $headersArray['X_MXIT_USERID_R'];
        }

        logger::debug("MxitUser.MxitUserID now set to: " . $MxitUserID);
        $this->setMxitUserId($MxitUserID);

        $this->displayName = urldecode($headersArray['X_MXIT_NICK']);

        $location = explode(',', $headersArray['X_MXIT_LOCATION']);
        /* Location:
          0 [ISO 3166-1 alpha-2 Country Code],
          1 [Country Name],
          2 [Principal Subdivision Code],
          3 [Principal Subdivision Name],
          4 [City Code],
          5 [City],
          6 [Network Operator Id],
          7 [Client Features Bitset],
          8 [Cell Id]
         */
        $this->currentCountryCode = $location[0];
        $this->currentRegionCode = $location[2];
        //$this->currentRegionName = $location[3];
        $this->currentCityCode = $location[4];
        $this->currentCityName = $location[5];
        $this->currentMNOCode = $location[6];
        $this->deviceFeature = $location[7];
        $this->currentMNOCellID = $location[8];

        $profile = explode(',', $headersArray['X_MXIT_PROFILE']);
        //Profile:
        /*
          0 [ISO_639-1 or ISO_639-2 language code],
          1 [user’s registered ISO 3166-1 alpha-2 Country Code],
          2 [short date of birth as YYYY-MM-dd],
          3 [gender string],
          4 [tariff_plan]
         */
        $this->languageCode = $profile[0];
        $this->registeredCountry = $profile[1];
        $this->dateOfBirth = new DateTime($profile[2]);
        $this->genderCode = ($profile[3] == 'Male') ? 1 : 0;
        $this->mxitTarrifPlan = $profile[4];
        $this->deviceUserAgent = urldecode($headersArray['X_DEVICE_USER_AGENT']);

        //IP:
        $deviceIP = $headersArray['X_FORWARDED_FOR'];
        $this->deviceIP = $deviceIP;

        //mobileNumber
        //Device Screen size:
        $pixels = explode('x', $headersArray['UA_PIXELS']);
        $this->deviceWidth = $pixels[0];
        $this->deviceHeight = $pixels[1];

        //Visit Stats:
        $this->dateCurrentAccess = time();
        $this->datePreviousAccess = time();
        $this->dateRegistered = time();

        logger::debugEnd();
    }

    /** 	
     * Check if this Mxit user exists in the Database, and return the UserOID if he does.
     * @return int UserOID if the user exists
     */
    private function read_CheckUserExistsAndReadUserOID_FromDB($MxitUserID, &$outIsUserExistsInDB, &$outUserOID) {
        logger::debugStart();

        $success = false;

        //Wrap all database code in a try/catch block. This will catch any sql exceptions.
        try {
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            
            // get budget categories set for this user from DB:
            $sql = "
            SELECT 
                UserOID_usr,
                DatetimeRegistered_usr, 
                DatetimePreviousVisit_usr,
                DatetimeCurrentVisit_usr,
                VisitCount_usr
            FROM 
                " . config::$tableNamePrefix . "user
            WHERE 
                MxitUserID_usr = :MxitUserID;
            ";

            logger::sql($sql);
            $statement = $db->prepare($sql);
            
            if ($statement){
                logger::debug('Bind variable to place holder..');
                
                $statement->bindValue(':MxitUserID', $MxitUserID);
            }  else {
                logger::error('Preparing PDO statement failed! ');
                return false;
            }

            logger::debug('Executing SQL statement...');
            $executeSuccess = $statement->execute();
            
            if ($executeSuccess){
                logger::debug('load read data into array');
                $result = $statement->fetchAll();
                
                if (!empty($result)){
                    $outIsUserExistsInDB = true;
                    foreach ($result as $row) {
                        //Read the fields from the row:
                        $tmpUserOID = $row['UserOID_usr'];
                        $tmpDatetime_CurrentAccess = new DateTime($row['DatetimeCurrentVisit_usr']);
                        $tmpDatetime_PreviousAccess = new DateTime($row['DatetimePreviousVisit_usr']);
                        $tmpDatetime_Registered = new DateTime($row['DatetimeRegistered_usr']);
                        $tmpVisitCount = intval($row['VisitCount_usr']);

                        logger::debug("UserOID_usr = " . $tmpUserOID);
                        logger::debug("DatetimeCurrentVisit_usr = " . $tmpDatetime_CurrentAccess->format('Y/m/d H:i:s'));
                        logger::debug("DatetimePreviousVisit_usr = " . $tmpDatetime_PreviousAccess->format('Y/m/d H:i:s'));
                        logger::debug("DatetimeRegistered_usr = " . $tmpDatetime_Registered->format('Y/m/d H:i:s'));
                        logger::debug("VisitCount_usr = " . $tmpVisitCount);

                        //Assign the values read to this User object:
                        $this->setUserOID($tmpUserOID);
                        $outUserOID = $tmpUserOID;

                        $this->dateCurrentAccess = $tmpDatetime_CurrentAccess;
                        $this->datePreviousAccess = $tmpDatetime_PreviousAccess;
                        $this->dateRegistered = $tmpDatetime_Registered;
                        $this->visitCount = intval($tmpVisitCount);
                    } //foreach row                   
                    
                    $success = true;
                }else{
                    logger::debug('Could not find user in DB...');
                    $success = false;
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
        return $success;
    }

    private function persist_User_toDB() {
        logger::debugStart();

        $success = false;

        //Wrap all database code in a try/catch block. This will catch any sql exceptions.
        try {
            /*@var $db PDO */
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();           

            $sql = 'INSERT INTO 
                        ' . config::$tableNamePrefix . 'user 
                    ( 
                        MXitUserID_usr, 
                        MxitDisplayName_usr,
                        CurrentCountryCode_usr,
                        CurrentRegionCode_usr,
                        CurrentCityCode_usr,
                        CurrentCityName_usr, 
                        CurrentMNOCode_usr, 
                        CurrentMNOCellID_usr, 
                        LanguageCode_usr, 
                        RegisteredCountry_usr, 
                        DateOfBirth_usr, 
                        Gender_usr,
                        MxitTarrifPlan_usr,
                        UserAgentString_usr,
                        DeviceIP_usr,
                        MobileNumber_usr,
                        DeviceFeatures_usr, 
                        DeviceDisplayWidth_usr, 
                        DeviceDisplayHeight_usr, 
                        DatetimeRegistered_usr, 
                        DatetimePreviousVisit_usr,
                        DatetimeCurrentVisit_usr,
                        VisitCount_usr) 
                VALUES 
                    (
                        :MXitUserID,
                        :MxitDisplayName,
                        :CurrentCountryCode,
                        :CurrentRegionCode,
                        :CurrentCityCode,
                        :CurrentCityName,
                        :CurrentMNOCode,
                        :CurrentMNOCellID,
                        :LanguageCode,
                        :RegisteredCountry,
                        :DateOfBirth,
                        :Gender,
                        :MxitTarrifPlan,
                        :UserAgentString,
                        :DeviceIP,
                        :MobileNumber,
                        :DeviceFeatures,
                        :DeviceDisplayWidth,
                        :DeviceDisplayHeight,
                        :DatetimeRegistered,
                        :DatetimePreviousVisit,
                        :DatetimeCurrentVisit,
                        :VisitCount
                    );';

            logger::sql($sql);

            //Prepare the statement
            logger::debug('Preparing sql statement..');
            $statement = $db->prepare($sql);            

            //Bind the parameters for the query:
            if ($statement){
                logger::debug('binding paramenters..');
                
                $statement->bindValue(':MXitUserID', $this->mxitUserId,  PDO::PARAM_STR);
                $statement->bindValue(':MxitDisplayName',html_entity_decode($this->displayName) ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentCountryCode',$this->currentCountryCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentRegionCode',$this->currentRegionCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentCityCode',$this->currentCityCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentCityName',$this->currentCityName ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentMNOCode',$this->currentMNOCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentMNOCellID',$this->currentMNOCellID ,  PDO::PARAM_STR);
                $statement->bindValue(':LanguageCode',$this->languageCode ,  PDO::PARAM_STR);
                $statement->bindValue(':RegisteredCountry',$this->registeredCountry ,  PDO::PARAM_STR);
                $statement->bindValue(':DateOfBirth',$this->dateOfBirth->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':Gender',$this->genderCode ,  PDO::PARAM_INT);
                $statement->bindValue(':MxitTarrifPlan',$this->mxitTarrifPlan ,  PDO::PARAM_STR);
                $statement->bindValue(':UserAgentString',html_entity_decode($this->deviceUserAgent) ,  PDO::PARAM_STR);
                $statement->bindValue(':DeviceIP',$this->deviceIP ,  PDO::PARAM_STR);
                $statement->bindValue(':MobileNumber',$this->mobileNumber ,  PDO::PARAM_STR);
                $statement->bindValue(':DeviceFeatures',$this->deviceFeature ,  PDO::PARAM_INT);
                $statement->bindValue(':DeviceDisplayWidth',$this->deviceWidth ,  PDO::PARAM_INT);
                $statement->bindValue(':DeviceDisplayHeight',$this->deviceHeight ,  PDO::PARAM_INT);
                $statement->bindValue(':DatetimeRegistered',$this->dateRegistered->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':DatetimePreviousVisit',$this->datePreviousAccess->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':DatetimeCurrentVisit',$this->dateCurrentAccess->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':VisitCount',$this->visitCount ,  PDO::PARAM_INT);
            }  else {
                logger::error('Preparing PDO statement failed! ');
                return false;
            }           

            //Execute the statement:
            $executeSuccess = $statement->execute();
                        
            //Check if we got a result from the DB:
            if ($executeSuccess && $statement->rowCount() > 0){                      
                logger::debug("Inserted row to DB...");
                
                $insertedUserOID = $db->lastInsertId();                
                
                logger::debug("Setting this user's userOID to:" . $insertedUserOID);
                $this->UserOID = $insertedUserOID;
                
                $success = true;                
            }  else {
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                $success = false;
            }
        } catch (Exception $ex) {
            logger::error("Problem persisting user info: " . $ex);
        }

        logger::debugEnd();
        return $success;
    }

    function update_User_toDB() {
        logger::debugStart();

        $success = false;

        //Wrap all database code in a try/catch block. This will catch any mysqli exceptions.
        try {
            logger::debug('Getting DB connection..');
            /* $db PDO */
            $db = DatabasePDOHelper::getInstance()->getConnection();

            $sql = "
                    UPDATE
                        " . config::$tableNamePrefix . "user 
                    SET
                        MxitDisplayName_usr = :MxitDisplayName,
                        CurrentCountryCode_usr = :CurrentCountryCode,
                        CurrentRegionCode_usr = :CurrentRegionCode,
                        CurrentCityCode_usr = :CurrentCityCode,
                        CurrentCityName_usr = :CurrentCityName,
                        CurrentMNOCode_usr = :CurrentMNOCode,
                        CurrentMNOCellID_usr = :CurrentMNOCellID,
                        LanguageCode_usr = :LanguageCode,
                        RegisteredCountry_usr = :RegisteredCountry,
                        DateOfBirth_usr = :DateOfBirth,
                        Gender_usr = :Gender,
                        MxitTarrifPlan_usr = :MxitTarrifPlan,
                        UserAgentString_usr = :UserAgentString,
                        DeviceIP_usr = :DeviceIP,
                        MobileNumber_usr = :MobileNumber,
                        DeviceFeatures_usr = :DeviceFeatures,
                        DeviceDisplayWidth_usr = :DeviceDisplayWidth,
                        DeviceDisplayHeight_usr = :DeviceDisplayWidth,
                        DatetimeRegistered_usr = :DatetimeRegistered,
                        DatetimePreviousVisit_usr = :DatetimePreviousVisit,
                        DatetimeCurrentVisit_usr = :DatetimeCurrentVisit,
                        VisitCount_usr = :VisitCount 
                    WHERE
                        MxitUserID_usr = :MxitUserID;";

            logger::sql($sql);

            //Prepare the statement
            logger::debug('Preparing sql statement..');
            $statement = $db->prepare($sql);
            
            if ($statement){
                logger::debug('binding paramenters..');
                
                $statement->bindValue(':MXitUserID', $this->mxitUserId,  PDO::PARAM_STR);
                $statement->bindValue(':MxitDisplayName',html_entity_decode($this->displayName) ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentCountryCode',$this->currentCountryCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentRegionCode',$this->currentRegionCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentCityCode',$this->currentCityCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentCityName',$this->currentCityName ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentMNOCode',$this->currentMNOCode ,  PDO::PARAM_STR);
                $statement->bindValue(':CurrentMNOCellID',$this->currentMNOCellID ,  PDO::PARAM_STR);
                $statement->bindValue(':LanguageCode',$this->languageCode ,  PDO::PARAM_STR);
                $statement->bindValue(':RegisteredCountry',$this->registeredCountry ,  PDO::PARAM_STR);
                $statement->bindValue(':DateOfBirth',$this->dateOfBirth->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':Gender',$this->genderCode ,  PDO::PARAM_INT);
                $statement->bindValue(':MxitTarrifPlan',$this->mxitTarrifPlan ,  PDO::PARAM_STR);
                $statement->bindValue(':UserAgentString',html_entity_decode($this->deviceUserAgent) ,  PDO::PARAM_STR);
                $statement->bindValue(':DeviceIP',$this->deviceIP ,  PDO::PARAM_STR);
                $statement->bindValue(':MobileNumber',$this->mobileNumber ,  PDO::PARAM_STR);
                $statement->bindValue(':DeviceFeatures',$this->deviceFeature ,  PDO::PARAM_INT);
                $statement->bindValue(':DeviceDisplayWidth',$this->deviceWidth ,  PDO::PARAM_INT);
                $statement->bindValue(':DeviceDisplayHeight',$this->deviceHeight ,  PDO::PARAM_INT);
                $statement->bindValue(':DatetimeRegistered',$this->dateRegistered->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':DatetimePreviousVisit',$this->datePreviousAccess->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':DatetimeCurrentVisit',$this->dateCurrentAccess->format('Y/m/d H:i:s') ,  PDO::PARAM_STR);
                $statement->bindValue(':VisitCount',$this->visitCount ,  PDO::PARAM_INT);
            }  else {
                logger::error('Preparing PDO statement failed! ');
                return false;
            }
            
            logger::debug('Executing sql statement..');
            $executeSuccess = $statement->execute();
            
            if ($executeSuccess && $statement->rowCount() > 0){                      
                logger::debug("Updated row in DB...");
                $success = true;                
            }  else {
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }            
        } catch (Exception $ex) {
            logger::error("Problem update user row: " . $ex);
        }

        logger::debugEnd();
        return $success;
    }

    function update_User_VisitStats_toDB() {
        logger::debugStart();

        $success = false;

        $db = DatabasePDOHelper::getInstance()->getConnection();

        //Wrap all database code in a try/catch block. This will catch any mysqli exceptions.
        try {

            $sql = "
                    UPDATE
                        " . config::$tableNamePrefix . "user 
                    SET
                        DatetimePreviousVisit_usr = :DatetimePreviousAccess,
                        DatetimeCurrentVisit_usr = :DatetimeCurrentAccess,
                        VisitCount_usr = :VisitCount
                    WHERE
                        MxitUserID_usr = :MxitUserID
                    ;";

            logger::sql($sql);

            logger::debug('Preparing PDO statement...');
            $stmt = $db->prepare($sql);

            if (!$stmt) {
                logger::error('Preparing PDO statement failed! ');
                return false;
            }

            logger::debug('Binding query parameters...');
            $bindSuccess = $stmt->bindValue(':DatetimePreviousAccess', $this->datePreviousAccess->format('Y/m/d H:i:s'));
            $bindSuccess = $bindSuccess && $stmt->bindValue(':DatetimeCurrentAccess', $this->dateCurrentAccess->format('Y/m/d H:i:s'));
            $bindSuccess = $bindSuccess && $stmt->bindValue(':VisitCount', $this->visitCount, PDO::PARAM_INT);
            $bindSuccess = $bindSuccess && $stmt->bindValue(':MxitUserID', $this->mxitUserId);
            
            logger::debug("Setting VisitCount: ". $this->visitCount);
            logger::debug("Setting PreviousVisitTime: " . $this->datePreviousAccess->format('Y/m/d H:i:s'));
            logger::debug("Setting CurrentVisitTime: " . $this->dateCurrentAccess->format('Y/m/d H:i:s'));

            if (!$bindSuccess) {
                logger::error('Binding paramaters failed! ');
                return false;
            }

            logger::debug('Executing SQL statement...');
            $executeSuccess = $stmt->execute();       

            if (!$executeSuccess || $stmt->errorCode() > 0) {
                $errorArray = $stmt->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            } else {

                logger::debug('Checking number of rows affected...');
                //Check if we got a result from the DB:
                if ($stmt->rowCount() > 0) {
                    logger::debug("Update row to DB...");
                    $success = true;
                } else {
                    logger::error("Could not update user to DB! ");
                    $success = false;
                }
            }
        } catch (Exception $ex) {
            logger::error("Problem updating the user row: " . $ex);
        }

        logger::debugEnd();
        return $success;
    }

}

?>