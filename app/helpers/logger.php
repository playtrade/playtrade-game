<?php

class logger {

    static function appendErrorLine($currentDateTime, $callingClass, $callingMethod, $logText) {
        echo '<font size="3" face="courier" color="#000000">' . $currentDateTime . '</font>&nbsp;|&nbsp;' . '<font face="courier" color="#0000FF">' . $callingClass . '</font>&nbsp;|&nbsp;<font face="courier" color="#FF00FF">' . $callingMethod . '()</font>&nbsp;|&nbsp;<font face="courier" color="red">' . $logText . '</font><br/>';
    }

    static function appendLogLine($currentDateTime, $callingClass, $callingMethod, $logText) {
        echo '<font size="3" face="courier" color="#000000">' . $currentDateTime . '</font>&nbsp;|&nbsp;' . '<font face="courier" color="#0000FF">' . $callingClass . '</font>&nbsp;|&nbsp;<font face="courier" color="#FF00FF">' . $callingMethod . '()</font>&nbsp;|&nbsp;<font face="courier" color="00CC00">' . $logText . '</font><br/>';
    }

    static function appendLogLine_SQL($currentDateTime, $callingClass, $callingMethod, $sqlText) {
        logger::appendLogLine($currentDateTime, $callingClass, $callingMethod, "Executed SQL query:");
        echo '<font size="3" face="courier" color="#00CC00">' . $sqlText . '</font><br/>';
    }

    static function error($logText) {

        if (config::$isShowError) {
            //Get the trace
            $trace = debug_backtrace();

            //Get the calling class form the trace:
            if (isset($trace[1]['class'])) {
                $calling_class = $trace[1]['class'];
            } else {
                $calling_class = "NA";
            }

            //Get the calling method form the trace:
            $calling_method = $trace[1]['function'];
            if ($calling_method == 'include_once')
                $calling_method = "root";

            //Get the current time:
            $currentDateTime = logger::getCurrentDateTimeWithMilliseconds();

            logger::appendErrorLine($currentDateTime, $calling_class, $calling_method, $logText);
        }
    }

    static function debug($logText) {

        if (config::$isShowDebug) {
            //Get the trace
            $trace = debug_backtrace();

            //Get the calling class form the trace:
            if (isset($trace[1]['class'])) {
                $calling_class = $trace[1]['class'];
            } else {
                $calling_class = "NA";
            }

            //Get the calling method form the trace:
            $calling_method = $trace[1]['function'];
            if ($calling_method == 'include_once')
                $calling_method = "root";

            //Get the current time:
            $currentDateTime = logger::getCurrentDateTimeWithMilliseconds();

            logger::appendLogLine($currentDateTime, $calling_class, $calling_method, $logText);
        }
    }

    static function sql($sqlText) {

        if (config::$isShowSQL) {
            //Get the trace
            $trace = debug_backtrace();

            //Get the calling class form the trace:
            $calling_class = $trace[1]['class'];
            //Get the calling method form the trace:
            $calling_method = $trace[1]['function'];
            //Get the current time:
            $currentDateTime = logger::getCurrentDateTimeWithMilliseconds();

            logger::appendLogLine_SQL($currentDateTime, $calling_class, $calling_method, $sqlText);
        }
    }

    static function debugStart() {

        if (config::$isShowDebug) {
            //Get the trace
            $trace = debug_backtrace();

            //Get the calling class form the trace:
            if (isset($trace[1]['class'])) {
                $calling_class = $trace[1]['class'];
            } else {
                $calling_class = "NA";
            }
            
            //Get the calling method form the trace:
            $calling_method = $trace[1]['function'];
            //Get the current time:
            $currentDateTime = logger::getCurrentDateTimeWithMilliseconds();

            logger::appendLogLine($currentDateTime, $calling_class, $calling_method, "START");
        }
    }

    static function debugEnd() {

        if (config::$isShowDebug) {
            //Get the trace
            $trace = debug_backtrace();

            //Get the calling class form the trace:
            if (isset($trace[1]['class'])) {
                $calling_class = $trace[1]['class'];
            } else {
                $calling_class = "NA";
            }
            
            //Get the calling method form the trace:
            $calling_method = $trace[1]['function'];
            //Get the current time:
            $currentDateTime = logger::getCurrentDateTimeWithMilliseconds();

            logger::appendLogLine($currentDateTime, $calling_class, $calling_method, "END");
        }
    }

    static function getCurrentDateTimeWithMilliseconds() {
        $m = explode(' ', microtime());
        list($totalSeconds, $extraMilliseconds) = array($m[1], (int) round($m[0] * 1000, 3));
        $timeWithMilliseconds = date("d-m-Y H:i:s", $totalSeconds) . ".$extraMilliseconds\n";
        return $timeWithMilliseconds;
    }

}

?>