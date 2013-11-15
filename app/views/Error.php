<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <?php
        $pageTitle = "Paytrade";
        echo mxitViewHelper::appendPageHeader($pageTitle);
        ?>
        <h4><?php echo $title ?></h4      
        <p style="color:red"><?= $error ?></p>

        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>
            
    </body>

</html>