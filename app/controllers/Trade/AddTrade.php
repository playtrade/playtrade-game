<?php

function _AddTrade(){
    
    if(isset($_POST['submit']))
        handleAddTrade();
    else
        handleAddTradeForm ();
    
}

function handleAddTradeForm()
{
    $view = new View(APP_PATH . 'views/Trade/AddTrade.php',array('title'=>'Add new Company'));
    $view->dump();
}

function handleAddTrade()
{
    $trade = new Trade();
    
    //=== Start geeting $_POST values for trade === /
    //Set trade name
    if(isset($_POST['TradeName'])&&!empty($_POST['TradeName'])){
        $TradeName = $_POST['TradeName'];
    }else{
        $view = new View(APP_PATH . 'views/Error.php');
        $vars = array('title'=>'Create Company','error' =>'Trade Name cannot be empty');
        $view->dump($vars);
    }
    
    //Set Trade description
    if(isset($_POST['TradeDesciption'])&&!empty($_POST['TradeDesciption'])){
        $Description = $_POST['TradeDesciption'];
    }else{
        renderErrorView(array('error' =>'Description cannot be empty'));
        return false;
    }
    
    //Set Item category
    if(isset($_POST['ItemCategory'])&&!empty($_POST['ItemCategory'])){
        $ItemCategory = $_POST['ItemCategory'];
    }else{
        renderErrorView(array('error' =>'Item category cannot be empty'));
        return false;
    }

    //Set Item category
    if(isset($_POST['TradeCategory'])&&!empty($_POST['TradeCategory'])){
        $TradeCategory = $_POST['TradeCategory'];
    }else{
        renderErrorView(array('error' =>'Trade category cannot be empty'));
        return false;
    }    
   
    if(isset($_SESSION['user']->UserOID)&&!empty($_SESSION['user']->UserOID)){
        $UserOID = $_SESSION['user']->UserOID;
    }else{
        if(isset($_SESSION['UserOID'])){
            $UserOID = $_SESSION['UserOID'];
        }else{            
            renderErrorView(array('error' =>'User cannot be looged')); //to be change
            return false;
        }
          
    }
    
    $Trade = new Trade();
    $DateCreated = date("Y-m-d");
    $TimeCreated = date("H:i:s");
    $UserOID = $_SESSION['user']->UserOID;
    
    $Trade->setTradeName($TradeName);
    $Trade->setDescription($Description);
    $Trade->setUserOID($UserOID);
    $Trade->setStutus(0); // 0 active
    $Trade->setDateCreated($DateCreated);
    $Trade->setTimeCreated($TimeCreated);
    $Trade->setCategoryOID($ItemCategory);
    
    $ManageTrade = new ManageTrade();
    
    
    if($ManageTrade->addTrade($Trade,$UserOID)){
        renderSuccessView (array('Message'=>'Trade successfully created.'));
    }else{
        renderErrorView(array('Message'=>'Error occured while createing Trade.')); 
        return FALSE;
    }       
}


/**
 * @desc Check if an index is valid in a array and if the value is not empty
 * @param type $values
 * @return boolean TRUE if index valid and value not empty, If index is not valide
 *         or value is empty, return FALSE.
 */
function ValidateField($values)
{
    
}
