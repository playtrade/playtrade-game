<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuizGameCache
 *
 * @author Mongezi
 */
class QuizGameCache {
    /**
     * List of Quiz objects
     *
     * @var array(QuizID => Quiz)
     */
    private $QuizList = array();
    
    /**
     * List of QuizGames currently being played
     *
     * @var array(QuizGameID => QuizGame)
     */
    private $QuizGameList = array();
    
    /**
     * A static member variable representing the class instance
     *
     * @var QuizGameCache
     */
    private static $_instance = null;
    
    private function __construct() {
        $this->populateDataObjects();
    }

    /**
     * Do not clone.
     */
    public function __clone() {
        logger:error("Cannot clone instance of Singleton pattern ...");
    }

    /**
     * Do not wakeup.
     */
    public function __wakeup() {
        logger:error("Cannot deserialize instance of Singleton pattern ...");
    }

    /**
     * Single globally accessible static method that returns the instance of this object
     */
    public static function getInstance() {
        logger::debugStart();
        logger::debug("Checking if singleton exists...");
        
        if (self::$_instance === null) {
            logger::debug("Could not get instance of QuizGameCache, creating instance...");

            try {
                self::$_instance = new QuizGameCache();
                
            } catch (Exception $ex) {
                logger::error("Problem creating singleton: " . $ex);
            }
            
        } else {
            logger::debug("Found instance of QuizGameCache singleton, not creating again...");
        }

        logger::debugEnd();
        return self::$_instance;
    }
    
    private function populateDataObjects(){
        logger::debugStart();

        try {
            logger::debug("Read the data from memcache if possible...");
            // Connection constants
            //define('MEMCACHED_HOST', 'unxdev02.kazazoom.com');
            //define('MEMCACHED_PORT', '11211');

            $isMemcacheExtensionLoaded = extension_loaded('Memcache');
            $isMemcachedExtensionLoaded = extension_loaded('Memcached');
            logger::debug("Check if the config file says we should useMemcache: " . config::$isUseMemcache);
            logger::debug("Check if Memcache extension loaded:" . $isMemcacheExtensionLoaded);

            if (config::$isUseMemcache) {

                logger::debug("Checking if Memcache extensions is loaded...");

                if ($isMemcacheExtensionLoaded || $isMemcachedExtensionLoaded) {

                    if ($isMemcacheExtensionLoaded)
                        $memcache = new Memcache;
                    else
                        $memcache = new Memcached;

                    logger::debug('Connecting to memcache server (' . config::$memcachedHost . ':' . config::$memcachedPort . ')');
                    $isConnectedToMemcache = $memcache->connect(config::$memcachedHost, config::$memcachedPort, 180);

                    $memcache_key = config::$appContactName . "." . config::getEnvironment() . '.quizListCache.v1';

                    logger::debug("Setting unique key for memcache entry: " . $memcache_key);

                    logger::debug("Checking if we are connected to Memcache...");
                    if ($isConnectedToMemcache == true) {
                        logger::debug("Connected to memcache...");

                        logger::debug("Get the data from the cache server for key: " . $memcache_key);
                        $DataFromCache = $memcache->get($memcache_key);

                        // Check the data received is valid:
                        $isCacheItemFound = isset($DataFromCache) && $DataFromCache != "";

                        logger::debug("Check if the Cache Item was found...");

                        if ($isCacheItemFound) {

                            logger::debug("Found " . $memcache_key . " in memcache :-) ...");

                            logger::debug("Unserialize the object from Memcache...");

                            $this->QuizList = unserialize($DataFromCache);
                        } else {
                            logger::debug("Could NOT get data for key (" . $memcache_key . ") from memcache...");

                            logger::debug("Reading Data from DB");
                            $this->read_QuizList_fromDB();

                            logger::debug("Serialize the object...");
                            $serializedData = serialize($this->QuizList);

                            logger::debug("Set serializedData into memcache key: " . $memcache_key);
                            if ($memcache instanceof Memcache){
                                logger::debug('Memcache instance: Memcache');
                                $memcache->set($memcache_key, $serializedData, 0, config::$QuizGameCache_LifeTime); 
                            }  else {
                                logger::debug('Memcache instance: Memcached');
                                $memcache->set($memcache_key, $serializedData, config::$QuizGameCache_LifeTime);
                            }
                            

                            logger::debug("Finished sending the serialized data to memcache.");
                        }
                    } else {
                        logger::error("Could not connect to memcache server, reading Data from DB");

                        logger::debug("Reading Data from DB");
                        $this->read_QuizList_fromDB();
                    }
                } else {
                    logger::error("Memcache or Memcached module not loaded.");
                    $this->read_QuizList_fromDB();
                }
            } else {
                logger::debug("Config says we should NOT use Memcache...");

                logger::debug("Going to read data from DB because we are not using Memcache...");
                $this->read_QuizList_fromDB();
            }
        } catch (Exception $e) {
            logger:error("Problem populating the data objects: " + $ex);
        }

        logger::debugEnd();        
    }
    
    private function read_QuizList_fromDB(){
        logger::debugStart();        
        $success = false;
        
        try{
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            $sql = "
                    SELECT
			QuizOID_Ltst,
                        ProductOID_Ltst,
                        NumOfQuestionsToAsk_Ltst,
                        OrderBy_Ltst,
                        LanguageCode_Ltst,
                        ForGenderPreference_Ltst,
                        Name_Ltst,
                        Description_Ltst,
                        MaxScore_Ltst,
                        PublishDateTime_Ltst,
                        PublishLifecycleStateOID_Ltst,
                        CreatedDateTime_Ltst,
                        ModifiedDateTime_Ltst,
                        CreatedAuthor_Ltst,
                        ModifiedAuthor_Ltst,
                        HasGameRanking_Ltst,
                        DetailTypeOID_Ltst,
                        IsCompetitionActive_Ltst,
                        TermsAndConditions_Ltst,
                        VirtualItemOID_Ltst,
                        PreselectedAdUnitID_Ltst
                    FROM 
			" . config::$tableNamePrefix . "quiz
                    WHERE 
                        PublishLifecycleStateOID_Ltst = :PublishLifecycleStateID
                        AND
                        date(PublishDateTime_Ltst) = date(:PublishDateTime)
                    ;";
            logger::debug($sql);
            
            logger::debug('Preparing PDO statement');
            $statement = $db->prepare($sql);
            
            //check if statement prepared fully
            if ($statement) {
                logger::debug('Bind variable to place holder..');
                $today = date('Y:m:d');
//                $monthAgo = new DateTime();
//                $monthAgo->sub(new DateInterval(('P30D')));
//                $monthAgo = $monthAgo->format('Y:m:d'); 
                $lifeCycleState = 1;
                
                //logger::debug('state: ' . $lifeCycleState . ' date: ' . $today . ' since: ' . $monthAgo);
                $statement->bindValue(':PublishDateTime', $today);
                //$statement->bindValue(':monthAgo', $monthAgo);
                $statement->bindValue(':PublishLifecycleStateID', $lifeCycleState, PDO::PARAM_INT);
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
                    foreach ($result as $row) {
                        $Quiz = new Quiz();
                        
                        logger::debug('quiz ID: ' . $row['QuizOID_Ltst']);
                        $Quiz->quizID = $row['QuizOID_Ltst'];
                        $Quiz->productID = $row['ProductOID_Ltst'];
                        $Quiz->numOfQuestionsToAsk = $row['NumOfQuestionsToAsk_Ltst'];
                        $Quiz->orderBy = $row['OrderBy_Ltst'];
                        $Quiz->languageCode = $row['LanguageCode_Ltst'];
                        $Quiz->forGenderPreference = $row['ForGenderPreference_Ltst'];
                        $Quiz->name = $row['Name_Ltst'];
                        $Quiz->description = $row['Description_Ltst'];
                        $Quiz->maxScore = $row['MaxScore_Ltst'];
                        $Quiz->publishDateTime = $row['PublishDateTime_Ltst'];
                        $Quiz->publishLifecycleStateID = $row['PublishLifecycleStateOID_Ltst'];
                        $Quiz->createdDateTime = $row['CreatedDateTime_Ltst'];
                        $Quiz->modifiedDateTime = $row['ModifiedDateTime_Ltst'];
                        $Quiz->createdAuthor = $row['CreatedAuthor_Ltst'];
                        $Quiz->modifiedAuthor = $row['ModifiedAuthor_Ltst'];
                        $Quiz->hasGameRanking = $row['HasGameRanking_Ltst'];
                        $Quiz->detailTypeID = $row['DetailTypeOID_Ltst'];
                        $Quiz->isCompetitionActive = $row['IsCompetitionActive_Ltst'];
                        $Quiz->termsAndConditions = $row['TermsAndConditions_Ltst'];
                        $Quiz->virtualItemID = $row['VirtualItemOID_Ltst'];
                        $Quiz->preselectedAdUnitID = $row['PreselectedAdUnitID_Ltst'];
                        
                        $this->QuizList[$Quiz->quizID] = $Quiz;
                        
                        //get quiz questions
                        $this->read_QuizQuestions_fromDB($Quiz->quizID);
                        
                        //get quiz results
                        $this->read_QuizResults_fromDB($Quiz->quizID);
                        
                    }// foreach row
                }//if result 
                else {
                    logger::debug('No quiz available');
                }
            }  else {                
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }
            $success = true;
        }  catch (Exception $ex){
            logger::debug('Problem getting quizes from DB..' . $ex);
            return false;
        }
                
        logger::debugEnd();
        return $success;
    }
    
    private function read_QuizQuestions_fromDB($quizId){
        logger::debugStart();        
        $success = false;
        
        try{
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            $sql = "
                    SELECT
			QuestionOID_Qst,
                        QuizOID_Qst,
                        QuestionText_Qst,
                        AnswerAText_Qst,
                        AnswerBText_Qst,
                        AnswerCText_Qst,
                        AnswerDText_Qst,
                        AnswerAScore_Qst,
                        AnswerBScore_Qst,
                        AnswerCScore_Qst,
                        AnswerDScore_Qst,
                        AnswerABlurb_Qst,
                        AnswerBBlurb_Qst,
                        AnswerCBlurb_Qst,
                        AnswerDBlurb_Qst
                    FROM 
			" . config::$tableNamePrefix . "quiz_question
                    WHERE 
                        QuizOID_Qst = :QuizID
                    ;";
            logger::debug($sql);
            
            logger::debug('Preparing PDO statement');
            $statement = $db->prepare($sql);
            
            //check if statement prepared fully
            if ($statement) {
                logger::debug('Bind variable to place holder..');
                $statement->bindValue(':QuizID', $quizId, PDO::PARAM_INT);
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
                    $i = 1;
                    foreach ($result as $row) {
                        $question = new QuizQuestion();
                        
                        logger::debug('question id: ' . $row['QuestionOID_Qst']);
                        $question->questionID = $row['QuestionOID_Qst'];
                        $question->quizOID = $row['QuizOID_Qst'];
                        $question->questionText = $row['QuestionText_Qst'];
                        $question->answerText['A'] = $row['AnswerAText_Qst'];
                        $question->answerText['B'] = $row['AnswerBText_Qst'];
                        $question->answerText['C'] = $row['AnswerCText_Qst'];
                        $question->answerText['D'] = $row['AnswerDText_Qst'];
                        $question->answerScore['A'] = $row['AnswerAScore_Qst'];
                        $question->answerScore['B'] = $row['AnswerBScore_Qst'];
                        $question->answerScore['C'] = $row['AnswerCScore_Qst'];
                        $question->answerScore['D'] = $row['AnswerDScore_Qst'];
                        $question->answerBlurb['A'] = $row['AnswerABlurb_Qst'];
                        $question->answerBlurb['B'] = $row['AnswerBBlurb_Qst'];
                        $question->answerBlurb['C'] = $row['AnswerCBlurb_Qst'];
                        $question->answerBlurb['D'] = $row['AnswerDBlurb_Qst'];

                        $this->QuizList[$quizId]->questions[$i] = $question;
                        $i++;
                    }// foreach row
                }//if result 
                else {
                    logger::debug('No questions available for quiz');
                }
            }  else {
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }
            $success = true;
        }  catch (Exception $ex){
            logger::debug('Problem getting quiz questions from DB..' . $ex);
            return false;
        }
                
        logger::debugEnd();
        return $success;
    }
    
    private function read_QuizResults_fromDB($quizId){
        logger::debugStart();
        $success = false;
        
        try{
            logger::debug('Getting DB connection..');
            $db = DatabasePDOHelper::getInstance()->getConnection();
            $sql = "
                    SELECT
                        QuizResultOID_Qres,
                        QuizOID_Qres,
                        ResultScoreMinRange_Qres,
                        ResultScoreMaxRange_Qres,
                        ResultShortTextBlurb_Qres,
                        ResultLongTextBlurb_Qres
                    FROM 
			" . config::$tableNamePrefix . "quiz_result
                    WHERE 
                        QuizOID_Qres = :QuizID
                    ;";
            logger::debug($sql);
            
            logger::debug('Preparing PDO statement');
            $statement = $db->prepare($sql);
            
            //check if statement prepared fully
            if ($statement) {
                logger::debug('Bind variable to place holder..');
                $statement->bindValue(':QuizID', $quizId, PDO::PARAM_INT);
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
                    foreach ($result as $row) {
                        $result = new QuizResult();
                        
                        $result->quizResultID = $row['QuizResultOID_Qres'];
                        $result->quizID = $row['QuizOID_Qres'];
                        $result->resultScoreMinRange = $row['ResultScoreMinRange_Qres'];
                        $result->resultScoreMaxRange = $row['ResultScoreMaxRange_Qres'];
                        $result->resultShortTextBlurb = $row['ResultShortTextBlurb_Qres'];
                        $result->resultLongTextBlurb = $row['ResultLongTextBlurb_Qres'];


                        $this->QuizList[$quizId]->results[$result->quizResultID] = $result;
                    }// foreach row
                }//if result 
                else {
                    logger::debug('No quiz results are available..');
                }
            }  else {
                $errorArray = $statement->errorInfo();
                logger::error("Error executing prepared statement: " . $errorArray[2]);
                return false;
            }
            $success = true;
        }  catch (Exception $ex){
            logger::debug('Problem getting quiz results from DB..' . $ex);
            return false;
        }
                
        logger::debugEnd();
        return $success;
    }
    
    public function getQuizGame($quizGameId){
        logger::debugStart();
    
        if (isset($this->QuizGameList[$quizGameId])){
            logger::debug('quizGame already exists: ' . $quizGameId);
            
            logger::debugEnd();
            return $this->QuizGameList[$quizGameId];
        }  else {
            logger::debug('current game not in list. create new quizGame: ' . $quizGameId);
            $randomQuizId = array_rand($this->QuizList);
            if (!empty($this->QuizList[$randomQuizId])){
                logger::debug('random quizId: ' . $randomQuizId);
                $newQuiz = $this->QuizList[$randomQuizId];
                $newQuizGame = new QuizGame();  
                $newQuizGame->GetQuizGame($newQuiz);
                $this->QuizGameList[$quizGameId] = $newQuizGame; 
                
                logger::debugEnd();
                return $newQuizGame;
            }  else {
                logger::debugEnd();
                return NULL;
            }
        }//if user doesn't has game in session  
    }
    
//    public function endQuizGame($quizGameId){
//        logger::debugStart();
//        
//        if (isset($this->QuizGameList)){
//            if (isset($this->QuizGameList[$quizGameId])){
//                //quizGame exists
//                $this->QuizGameList[$quizGameId]->EndGame();
//                $endingGame = $this->QuizGameList[$quizGameId];
//                unset($this->QuizGameList[$quizGameId]);
//                return $endingGame->QuizGameResult;
//            }  else {
//                //no such game exists
//                return FALSE;
//            }//if user doesn't has game in session
//        }// if list exists
//        
//        logger::debugEnd();
//        return FALSE;
//    }
//    
//    public function getNextQuizQuestion($quizGameId){
//        logger::debugStart();
//        
//        if (isset($this->QuizGameList)){
//            if (isset($this->QuizGameList[$quizGameId])){
//                //game exists
//                $nextQuestion = $this->QuizGameList[$quizGameId]->GetNextQuestion();
//                return $nextQuestion;
//            }  else {
//                //game doesnt exist
//                return FALSE;
//            }
//        }//if games exist
//        return FALSE; //no games in progress
//        
//        logger::debugEnd();
//    }
//    
//    public function checkQuizQuestionAnswer($quizGameid, $answerChar){
//        logger::debugStart();
//        
//        if (isset($this->QuizGameList)){
//            if (isset($this->QuizGameList[$quizGameid])){
//                //game exists
//                $answerBlurb = $this->QuizGameList[$quizGameid]->CheckAnswer($answerChar);
//                return $answerBlurb;
//            }  else {
//                //game doesnt exist
//                return FALSE;
//            }
//        }//if games exist
//        
//        logger::debugEnd();
//        return FALSE; //no games in progress               
//    }
}

?>
