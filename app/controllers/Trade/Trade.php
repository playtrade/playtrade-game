<?php

function  _Trade($Trade_OID)
{
    handleTrade($Trade_OID);
}

function handleTrade($Trade_OID)
{
    $ManageTrade = new ManageTrade();
    
    $Trade = $ManageTrade->getTrade($Trade_OID);
 
    $Bid_count = $ManageTrade->getNumberOfTradeBids($Trade_OID);
    $Name = $Trade->getTradeName();
    $view = new View(APP_PATH . 'views/Trade/Trade.php',array('title'=>"Viewing - $Name",'Trade'=>$Trade,'Bid_Count'=>$Bid_count));
    $view->dump();
}