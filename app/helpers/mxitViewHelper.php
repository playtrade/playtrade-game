<?php

class mxitViewHelper {

    static function getHeadTags() {
        $output = '
        <head>
            <title>' . $GLOBALS['sitename'] . '</title>
            <meta name="mxit" content="clear_on_new,no_prefix,show_progress" />
            <meta name="robots" content="noindex,nofollow"/>
        </head>';

        return $output;
        //<meta name="mxit" content="no_inline_images"/>
    }

    static function getBodyTags() {
        return 'style="background-color:#FFFFFF; color:#888888;"';
    }

    static public function appendHeading($heading) {
        echo '<span style="color:#888888;"><b>' . $heading . '</b></span><br />';
    }

    static public function appendPageHeader($pageHeading) {

        mxitViewHelper::appendLogo();
        echo "<br/>";
        //mxitViewHelper::appendHeading(config::$appNameString);
        //echo "<br/>";
        mxitViewHelper::appendHeading($pageHeading);
    }

    static public function appendLogo() {

        if (config::$isShowLogoBanner) {
            global $uwidth, $_SERVER;

            //Get the user from the PHP session:
            $User = $_SESSION['user'];
            $deviceWidth = $User->getDeviceWidth();
            $deviceUserAgent = $User->getDeviceUserAgent();

            switch (true) {
                case ($deviceWidth >= 480):
                    $img_filename = 'strap_480';
                    break;
                case ($deviceWidth >= 320):
                    $img_filename = 'strap_320';
                    break;
                case ($deviceWidth >= 240):
                    $img_filename = 'strap_240';
                    break;
                case ($deviceWidth >= 168):
                    $img_filename = 'strap_168';
                    break;
                case ($deviceWidth >= 128):
                    $img_filename = 'strap_128';
                    break;
                default:
                    $img_filename = 'strap_60';
                    break;
            }
            if ((stripos(' ' . $deviceUserAgent, 'ipad')) || (stripos(' ' . $deviceUserAgent, 'iphone'))) {
                if ($deviceWidth <= 480) {
                    $img_filename = 'strap_240'; //
                } elseif ($deviceWidth > 600) {
                    $img_filename = 'starp_480';
                }
            }

            echo '<img src="' . WEB_FOLDER . 'images/'. $img_filename . '.png" style="float:none"/><br/>';
        }
    }

// @ $destinations array of $label => $url
    static public function appendFooterNavigation($destinations = array(), $exclude_defaults = array(), $showBottomMenu = false) {

        if ($showBottomMenu) {
            mxitViewHelper::appendBottomMenu();
            echo "<br/>";
        }

//        $default_destinations = array(
//            'Back' => dirname($_SERVER['REQUEST_URI']),
//            'Menu' => WEB_FOLDER,
//        );
        
        $default_destinations = array(
            'Back' => $_SERVER['HTTP_REFERER'],
            'Menu' => WEB_FOLDER,
        );

        $output_array = array();

        foreach ($default_destinations as $label => $url) {
            if (!in_array($label, $exclude_defaults)) {
                if (key_exists($label, $destinations)) {
                    $output_array[] = "<a href=\"" . $destinations[$label] . "\">$label</a>";
                    unset($destinations[$label]);
                } else {
                    $output_array[] = "<a href=\"$url\">$label</a>";
                }
            }
        }

        foreach ($destinations as $label => $url) {
            $output_array[] = "<a href=\"$url\">$label</a>";
        }

        echo implode(' | ', $output_array);
        echo '<br/>';
        echo '<br/>';

        if (config::$isShowCopyrightFooter) {
            echo "&copy; " . date('Y', time()) . " " . config::$copyrightText;
        }

        return;
    }

    static public function appendHtmlTableStartTag($numOfColumns = 1) {

        //$output = "<table title=\"mxit:table:full\" border=\"1\" style=\"padding:0px;border-spacing:0;\"><colgroup span=\"$numOfColumns\" width=\"100%\"></colgroup>";
        $output = "<table title=\"mxit:table:full\" border=\"1\" style=\"padding:0px;border-spacing:0;\">";

        echo $output;
    }

    static public function appendHtmlTableRow($tableRowArray = array(), $isAlternateRow = false, $isHeaderRow = false) {

        $output = '<tr>';
        if ($isHeaderRow) {
            $backgroundStyle = "background-color:#009999;color:#ffffff;padding:1px 3px;text-align:left;border-width:1px 1px 1px 1px;border-style:solid;border-color:#009999;border-spacing:0;";
        } else {
            if (!$isAlternateRow) {
                $backgroundStyle = "background-color:#e7e7e7;color:#666666;padding:1px 3px;text-align:left;border-width:0px 1px 1px 1px;border-style:solid;border-color:#009999;border-spacing:0;";
            } else {
                $backgroundStyle = "background-color:#FFFFFF;color:#666666;padding:1px 3px;text-align:left;border-width:0px 1px 1px 1px;border-style:solid;border-color:#009999;border-spacing:0;";
            }
        }
        foreach ($tableRowArray as $tableData) {
            $output.= "<td style=\"$backgroundStyle\" >" . $tableData . '</td>';
        }
        $output .= '</tr>';
        echo $output;
    }



}

?>