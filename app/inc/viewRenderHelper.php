<?php

/**
 * @desc Renders a predefined success view.
 * @param type $options Associative array with values to be displayed
 */
function renderSuccessView($options = array('Message' =>'Operation successfully completed.'))
{
    $View = new View(APP_PATH . 'views/Company/CreateSuccess.php');
    $view->dump($options);
}

/**
 * @desc Renders a predefined error view.
 * @param type $options Associative array with values to be displayed
 */
function renderErrorView($options = array('Message' =>'An error occured while processing.'))
{
    $View = new View(APP_PATH . 'views/Error.php');    
    $view->dump($options);
}