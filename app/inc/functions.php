<?php

function myUrl($url = '', $fullurl = false) {
    $s = $fullurl ? WEB_DOMAIN : '';
    $s.=WEB_FOLDER . $url;
    return $s;
}

function redirect($url) {
    header('Location: ' . myUrl($url));
    exit;
}

function getDateinISOformat(){
	$tdate = date("Y-m-d", mktime(0,0,0, date("m"), date("d"), date("y")));
	$tdate = $tdate." ".date('H:i:s');
	return $tdate;
}



function isValidNumeric($value) { //function to check if the value entered by user is a valid numeral between 1 and 100000
    if ((int) $value == $value) {

        if (!is_numeric($value) || $value < 0 || $value > 100000) {
            return false;
        } else {
            return true;
        }
    } else {
        $numberOfDecimals = strlen($value) - strrpos($value, '.') - 1; // -1 to compensate for the zero-based count in strpos()

        if ($numberOfDecimals > 2) {
            return false;
        } else {
            if (!is_numeric($value) || $value < 0 || $value > 100000) {
                return false;
            } else {
                return true;
            }
        }
    }
}
?>
