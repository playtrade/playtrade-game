<?php

function _ViewTrades($User_OID)
{
    handleViewAll($User_OID);
}

/**
 * @desc Handles getting all trades from the 
 *       database and rendering the view.
 */
function handleViewAll($User_OID)
{
    /* Instantiate MangeTrade object used to get all trades. */
    $ManageUser  = new ManageUser();
    
    /* get all trades from the database. */
    $Trades = $ManageUser->getUserTrades($User_OID);
    echo '<pre>';
        print_r($Trades);
        echo '</pre>';
    $view = new View(APP_PATH . 'views/User/ViewTrades.php',array('Trades'=>$Trades, 'title'=>'My Trades.'));
    $view->dump();
}