<?php

function  _ViewAll()
{
    handleViewAll();
}

function handleViewAll()
{
    
    $ManageCompany  = new ManageCompany();
    
    $Companies = $ManageCompany->getAllCompanies();
    $view = new View(APP_PATH . 'views/Company/ViewAll.php',array('Companies'=>$Companies,'title'=>'All Comapnies'));
    $view->dump();
}