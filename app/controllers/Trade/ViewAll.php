<?php

function  _ViewAll()
{
    handleViewAll();
}

/**
 * @desc Handles getting all trades from the 
 *       database and rendering the view.
 */
function handleViewAll()
{
    /* Instantiate MangeTrade object used to get all trades. */
    $ManageTrade  = new ManageTrade();
    
    /* get all trades from the database. */
    $Trades = $ManageTrade->getAllTrades(); 
    $All_Trades_Count = count($Trades);
    
    $view = new View(APP_PATH . 'views/Trade/ViewAll.php',array('Trades'=>$Trades, 'title'=>'Viewing all trades.','AllTradeCount'=>$All_Trades_Count));
    $view->dump();
}