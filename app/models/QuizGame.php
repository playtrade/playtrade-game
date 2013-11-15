<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuizGame
 *
 * @author Mongezi
 */
class QuizGame {
    /**
    * Quiz game ID
    *
    * @var int
    */
   public $quizGameID;
   
   /**
    * Quiz for this QuizGame
    *
    * @var Quiz
    */
   public $Quiz;
   
   /**
    * Time taken to complete game
    *
    * @var int
    */
   public $timeTaken;
   
   /**
    * QuizGameResult
    *
    * @var QuizGameResult
    */
   public $QuizGameResult;
   
   /**
    * current score
    *
    * @var array
    */
   public $gameScore = array(); //array(questionID => score)
   
   /**
    * game state
    *
    * @var boolean
    */
   public $isGameOver;
   
   /**
    * start time of game
    *
    * @var DateTime
    */
   public $startTime;
   
   /**
    * end time of game
    *
    * @var DateTime
    */
   public $endTime;
   
   //public $currentQuestionID;
   
   public $currentQuestionNumber;

    public function GetQuizGame($quiz){
        logger::debugStart();
       
        $this->Quiz = $quiz;
        logger::debug('(get)quiz id: ' . $quiz->name);
       
        logger::debugEnd();
   }

   
   public function StartGame(){
       logger::debugStart();
       
       //$this->Quiz = $quiz;
       logger::debug('(start)quiz id: ' . $this->Quiz->name);
       $this->isGameOver = FALSE;
       $this->startTime = new DateTime();
       $this->currentQuestionNumber = 1;
       
       logger::debugEnd();
   }
   
   public function EndGame(){
       logger::debugStart();
              
       $this->endTime = new DateTime();
       $timeDifference = $this->endTime->getTimestamp() - $this->startTime->getTimestamp();
       $this->timeTaken = $timeDifference;
       $this->isGameOver = TRUE;
       
       //create new quiz gameresult
       $quizGameResult = new QuizGameResult();
       $quizGameResult->dateCompleted = new DateTime();
       $quizGameResult->quizID = $this->Quiz->quizID;
       $quizGameResult->score = $this->GetTotalScore();
       $quizGameResult->timeTaken = $this->timeTaken;
       
       $this->QuizGameResult = $quizGameResult;
       return $quizGameResult;
             
       logger::debugEnd();
   }
   
    public function CheckAnswer($answerChar){   
        logger::debugStart();
        
       /* @var $currentQuestion QuizQuestion */
       $currentQuestion = $this->Quiz->questions[$this->currentQuestionNumber];
       logger::debug('question text: ' . $currentQuestion->questionText);
       $answerBlurb = 'Game Over';
       $score = 0;
       
        if (!$this->isGameOver){
            logger::debug('user anser: ' . $answerChar);
            switch ($answerChar){
                case 'A':
                     $answerBlurb = $currentQuestion->answerBlurb['A'];
                     $score = $currentQuestion->answerScore['A'];
                     break;
                case 'B':
                     $answerBlurb = $currentQuestion->answerBlurb['B'];
                     $score = $currentQuestion->answerScore['B'];
                     break;
                case 'C':
                     $answerBlurb = $currentQuestion->answerBlurb['C'];
                     $score = $currentQuestion->answerScore['C'];
                     break;
                case 'D':
                     $answerBlurb = $currentQuestion->answerBlurb['D'];
                     $score = $currentQuestion->answerScore['D'];
                     break;           
            }
            
            logger::debug('prev q number: ' . $this->currentQuestionNumber);
            $this->gameScore[$this->currentQuestionNumber] = $score;
            $this->currentQuestionNumber += 1;
            //$this->currentQuestionID = $currentQuestion->questionID;
            logger::debug('curr q number: ' . $this->currentQuestionNumber);
            return $answerBlurb;
       } // if game not over
       return FALSE;
       logger::debugEnd();
   }
   
   public function GetTotalScore(){
       $totalScore = 0;
       foreach ($this->gameScore as $score) {
           $totalScore += $score;
       }
       
       return $totalScore;
   }
   
   public function GetNextQuestion(){        
        //$this->currentQuestionNumber += 1;
        /* @var $nextQuestion QuizQuestion */
        $nextQuestion = $this->Quiz->questions[$this->currentQuestionNumber];
        //$this->currentQuestionID = $nextQuestion->questionID;
        
        return $nextQuestion;
   }
   
   public function GetFirstQuestion(){ 
       logger::debugStart();
       
       logger::debug('quiz id' . $this->Quiz->quizID);
        //reset($this->Quiz->questions);
        /* @var $firstQuestion QuizQuestion */
        $firstQuestion = current($this->Quiz->questions);
       //$this->currentQuestionID = $firstQuestion->questionID;
        
        return $firstQuestion;
        
        logger::debugEnd();
   }
   
    public function getScorePercentage(){
        $score = $this->GetTotalScore();
        if ($score > 0)
            $percentage = intval(($score / $this->Quiz->maxScore) * 100);
        else
            $percentage = 0;
        
        return $percentage;
    }
}

?>
