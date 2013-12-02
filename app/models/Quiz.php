<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Quiz
 *
 * @author Mongezi
 */
class Quiz {
    public $quizID;
    public $productID;
    public $numOfQuestionsToAsk;
    public $orderBy;
    public $languageCode;
    public $forGenderPreference;
    public $name;
    public $description;
    public $maxScore;
    public $publishDateTime;
    public $publishLifecycleStateID;
    public $createdDateTime;
    public $modifiedDateTime;
    public $createdAuthor;
    public $modifiedAuthor;
    public $hasGameRanking;
    public $detailTypeID;
    public $isCompetitionActive;
    public $termsAndConditions;
    public $virtualItemID;
    public $preselectedAdUnitID;
    
    /**
    * Array of questions
    *
    * @var array()
    */
   public $questions = array(); //array(questionID => QuizQuestion)
   
   /**
    * Array of results. Used to 
    * determine how well the user did.
    *
    * @var array()
    */
   public $results = array(); // array(resultNumber => QuizResult)
}

?>
