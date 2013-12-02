<html>

    <?php echo mxitViewHelper::getHeadTags(); ?>
    <!--<body <?php echo mxitViewHelper::getBodyTags(); ?>-->
    <body>
        <h4>Playtrade</h4>       
        <b>&rightarrow; <a href="<?= WEB_FOLDER ?>Company/AddCompany">Create new company</a><br/></b>
        <b>&rightarrow; <a href="<?= WEB_FOLDER ?>Company/ViewAll"> View companies</a><br/></b>
        <b>&rightarrow; <a href="<?= WEB_FOLDER ?>Trade/AddTrade">Create trade</a><br/></b>
        <b>&rightarrow; <a href="<?= WEB_FOLDER ?>Trade/ViewAll">List available trades</a><br/></b>
        <br/>
        <b>&rightarrow; <a href="<?= WEB_FOLDER ?>Portal/MyPortal">My Portal</a></b>
        <!--<?php echo $_SESSION['user']->displayName ?>-->
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>
</html>