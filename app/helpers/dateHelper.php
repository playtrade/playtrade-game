<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dateHelper
 *
 * @author Eric
 */
class dateHelper {

    static function get_YearDay_FromUnixTime($dateTimeUnix) {
        return (date('Y', $dateTimeUnix) * 1000) + date('z', $dateTimeUnix);
    }

    static function get_YearWeek_FromUnixTime($dateTimeUnix) {
        return date('Y', $dateTimeUnix) . date('W', $dateTimeUnix);  //ISO-8601 - Week starts on Monday, need to check against SQL        
    }
    
    static function get_YearMonth_FromUnixTime($dateTimeUnix) {
        return (date('Y', $dateTimeUnix) * 100) + date('m', $dateTimeUnix); 
    }
    
    static function get_Year_FromUnixTime($dateTimeUnix) {
        return date('Y', $dateTimeUnix);
    }    
    
    static function getNiceDateFormat($dateTimeUnix) {
        ////comment from pankaj, why not put this method in functions so that it is accessible easily
        //PS: I have made a function in function.php file (inc folder) which prints the current datetime
        //in database freindly format(ISO format) 
        return date('d M Y', $dateTimeUnix);
    }
    
    static function getNiceDateFormatShort($dateTimeUnix) {
        ////comment from pankaj, why not put this method in functions so that it is accessible easily
        //PS: I have made a function in function.php file (inc folder) which prints the current datetime
        //in database freindly format(ISO format) 
        return date('d M', $dateTimeUnix);
    }
     
    static function getNiceDateTimeFormatShort($dateTimeUnix) {
        ////comment from pankaj, why not put this method in functions so that it is accessible easily
        //PS: I have made a function in function.php file (inc folder) which prints the current datetime
        //in database freindly format(ISO format) 
        return date('d M, H:i', $dateTimeUnix);
    } 
    
    /*
     * This method validates the given date and attempts to format it. 'j M'
     * 'j' : Day of the month without leading zeros.
     * 'M' : A short textual representation of a month, three letters.
     * 
     * @author Mongezi    
     * @return formatted date if successful, else return False
     */ 
    static function validateAndFormatDate($date){
        $newDate = FALSE;
        $format = 'j M';
        
        //attempts to format date, if given in 'j M' format. e.g 5 Mar
        $newDate = DateTime::createFromFormat('j M', $date);
        if ($newDate){
            return $newDate->format($format);
        }
        
        //attempts to format date, if given in 'j n' format. e.g 5 5
        $newDate = DateTime::createFromFormat('j n', $date);
        if ($newDate){
            return $newDate->format($format);
        }
        
        //attempts to format date, if given in 'j F' format. e.g 5 March
        $newDate = DateTime::createFromFormat('j F', $date);
        if ($newDate){
            return $newDate->format($format);
        }
        
        //attempts to format date, if given in 'j M Y' format. e.g 5 March 2013
        $newDate = DateTime::createFromFormat('j M Y', $date);
        if ($newDate){
            return $newDate->format($format);
        }
        
        //attempts to format date, if given in 'j/n/Y' format. e.g 5/5/2013
        $newDate = DateTime::createFromFormat('j/n/Y', $date);
        if ($newDate){
            return $newDate->format($format);
        }
        
        return $newDate;
    }
    
    /*
     * This method validates the given time and attempts to format it. 'H:i'
     * 'H' : 24-hour format of an hour with leading zeros.
     * 'i' : Minutes with leading zeros.
     * 
     * @author Mongezi    
     * @return formatted date if successful, else return False
     */ 
    static function validateAndFormatTime($time){
        $newTime = FALSE;
        $format = 'H:i';
        
        //attempts to format time, if given in 'g:i a' format. 5:30 pm
        $newTime = DateTime::createFromFormat('g:i a', $time);
        if ($newTime){
            return $newTime->format($format);
        }
        
        //attempts to format time, if given in 'ga' format. 5pm
        $newTime = DateTime::createFromFormat('ga', $time);
        if ($newTime){
            return $newTime->format($format);
        }
        
        //attempts to format time, if given in 'h:ia' format. 05:30 pm
        $newTime = DateTime::createFromFormat('h:ia', $time);
        if ($newTime){
            return $newTime->format($format);
        }
        
        //attempts to format time, if given in 'H:i' format. 17:30
        $newTime = DateTime::createFromFormat('H:i', $time);
        if ($newTime){
            return $newTime->format($format);
        }
        
        //attempts to format time, if given in 'g:i a' format. 05:30
        $newTime = DateTime::createFromFormat('G:i', $time);
        if ($newTime){
            return $newTime->format($format);
        }
        
        return $newTime;
    }
}

?>
