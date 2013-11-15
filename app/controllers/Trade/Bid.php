<?php


function  _Bid($task)
{
    if ($task==0){
        handleAddBid();
    }elseif ($task == 1) {
            handleUpdate();
    }
}

function handleUpdate()
{
    
}

function handleAddBid()
{
    if(!isset($_POST['Trade_Category']))
        handleBidIndex ();
    else
        handleBidCategory ();
}

function handleBidIndex()
{
    $ManageTrade = new ManageTrade();
    
    $Trade_OID = $_POST['Trade_OID'];
    
    $Trade = $ManageTrade->getTrade($Trade_OID);
    $Name = $Trade->getTradeName();
    $view = new View(APP_PATH . 'views/Trade/Bid.php',array('title'=>"Viewing - $Name",'Trade'=>$Trade));
    $view->dump();
}

function handleBidCategory()
{
    $ManageTrade = new ManageTrade();
    
    $Trade_OID = $_POST['Trade_OID'];
    $Trade_Category = $_POST['Trade_Category'];
    
    if($Trade_Category > 0)
    {
        $Trade = $ManageTrade->getTrade($Trade_OID);
        $Name = $Trade->getTradeName();
        $Bid_UserOID = $_SESSION['user']->UserOID;
        $Trade_UserOId = $Trade->getUserOID();
        
        $Bid = new Bid();
        
        $Bid->setBid_Status(0); // bid status 2 = pending
        $Bid->setBid_Time(date('H:s:i'));
        $Bid->setTrade_Offer($Trade_Category);
        $Bid->setTrade_User($Trade_UserOId);
        $Bid->setBid_User($Bid_UserOID);
        $Bid->setTrade_Rquest('BMW M3');
        
        $ManageUser = new ManageUser();
        $ManageUser->make_a_bid($Bid_UserOID, $Trade_OID, $Trade_Category);
        
        $view = new View(APP_PATH . 'views/Trade/BidSuccess.php',array('title'=>"Viewing - $Name",'Trade'=>$Trade,'Trade_Category'=>$Trade_Category));
        $view->dump();
    }   
}