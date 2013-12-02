<?php

class Company
{
    private $CompanyOID;
    private $Name;
    private $Description;
    
    public function getCompanyOID() {
        return $this->CompanyOID;
    }

    public function getName() {
        return $this->Name;
    }

    public function getDescription() {
        return $this->Description;
    }
    
    public function setCompanyOID($CompanyOID) {
        $this->CompanyOID = $CompanyOID;
    }
    
    public function setDescription($Description) {
        $this->Description = $Description;
    }

    public function setName($Name) {
        $this->Name = $Name;
    }
}