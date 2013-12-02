<html>

    <?php echo mxitViewHelper::getHeadTags(); ?>
    <!--<body <?php echo mxitViewHelper::getBodyTags(); ?>-->
    <body>
        <b><h4>My Portal</h4></b>   
        <b style="color: green">&RightArrow; <a href="<?= WEB_FOLDER ?>User/ViewTrades/<?= $_SESSION['user']->UserOID ?>">View My Trades</a> (<?= $UserTradesCount ?>)<br/></b>          
        <b style="color: red">&RightArrow; <a href="<?= WEB_FOLDER ?>Trade/ViewAll">List available trades</a> (<?= $AllTradesCount ?>)<br/></b>
        <b style="color: blue">&RightArrow; <a href="<?= WEB_FOLDER ?>Trade/AddTrade">Create trade</a><br/></b>
        <br/>
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>
</html>