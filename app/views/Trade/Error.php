<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <h4><?php echo $title ?></h4>
        <p style="color:red"><?php $error ?></p>

        footerNav();
        
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>