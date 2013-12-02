<?php


function _MyPortal()
{
    handlePortalIndex();
}

function handlePortalIndex()
{
    $User_OID = (isset($_SERVER['X-Mxit-USERID-R'])) ? $_SERVER['X-Mxit-USERID-R'] : 'Visitor';
    
    $Manageuser = new ManageUser();
    $ManageTrades = new ManageTrade();
    
    $AllTrades = $ManageTrades->getAllTrades();
    $UserTrades = $Manageuser->getUserTrades($_SESSION['user']->UserOID);
    
    $All_Trade_Count = count($AllTrades);
    $User_Trades_Count = count($UserTrades);
    
    $view = new View(APP_PATH . 'views/Portal/MyPortal.php',array('title'=>'User Portal','UserTradesCount'=>$User_Trades_Count,'AllTradesCount'=>$All_Trade_Count));
    $view->dump();
}