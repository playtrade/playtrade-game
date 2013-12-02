<?php
function _AddCompany($msg = 'controllers/Company/AddCompany'){
    
    if(isset($_POST['submit']))
        handleAddCompany();
    else
        handleAddCompanyForm();
}

function handleAddCompanyForm()
{
    $view = new View(APP_PATH . 'views/Company/AddCompany.php',array('title'=>'Add new Company'));
    $view->dump();
}

function handleAddCompany()
{
    
    $trade = new Trade();
    
    //=== Start geeting $_POST values for trade === /
    //Set Company name
    if(isset($_POST['CompanyName'])&&!empty($_POST['CompanyName'])){
        $CompanyName = $_POST['CompanyName'];
    }else{        
        renderErrorView(array('error' =>'Company Name cannot be empty','title'=>'Error'));
        return false;
    }
    
    //Set Company description
    if(isset($_POST['CompanyDescription'])&&!empty($_POST['CompanyDescription'])){
        $CompanyDescription = $_POST['CompanyDescription'];
    }else{
        renderErrorView(array('error' =>'Comapny Description cannot be empty','title'=>'Error'));
        return false;
    }
    // === end getting information from $_POST === //
    
    
    $Company = new Company(); // contain information about the compnay to be passed to the manage user class
    
    $Company->setName($CompanyName);
    $Company->setDescription($CompanyDescription);
    
    $ManageCompany = new ManageCompany();
    
    if($ManageCompany->addCompany($Company)){
        renderSuccessView (array('Message'=>'Company successfully created.'));
    }else{
        renderErrorView(array('Message'=>'Error occured while createing company'));
        return false;
    }       
    
}


/**
 * @desc Renders a predefined success view.
 * @param type $options Associative array with values to be displayed
 */
function renderSuccessView($options = array('Message' =>'Operation successfully completed.','title'=>'Success'))
{
    $View = new View(APP_PATH . 'views/Company/CreateSuccess.php',$options);
    $view->dump();
}

/**
 * @desc Renders a predefined error view.
 * @param type $options Associative array with values to be displayed
 */
function renderErrorView($options = array('error' =>'An error occured while processing.','title'=>'Eroor'))
{
    $view = new View(APP_PATH . 'views/Error.php',$options);    
    $view->dump();
}