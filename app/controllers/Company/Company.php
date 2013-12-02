<?php

function  _Company($companyOID)
{
    handleViewAll($companyOID);
}

function handleViewAll($companyOID)
{
    $view = new View(APP_PATH . 'views/Main/LandingMxitView.php');
    $view->dump();
}