<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuizQuestion
 *
 * @author Mongezi
 */
class QuizQuestion {
    public $questionID;
    public $quizOID;
    public $questionText;
    
    /**
    * Array of answers
    *
    * @var array()
    */
    public $answerText = array(); //array(Char => answerString)

    /**
    * Array of scores for answers
    *
    * @var array()
    */
    public $answerScore = array(); //array(Char => answerScore)
    
    /**
    * Array of blurbs for answers
    *
    * @var array()
    */
    public $answerBlurb = array(); //array(Char => answerBlurb)      
}

?>
