<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <h4><?php echo $title ?></h4>
        <p>You have successfully placed a bid.</p>

        <h5>Bid Summary</h5>
        <span>Trade ID: </span><?= $Trade->getTradeOID()?></span><br />
        <span>Trade Name: </span><?= $Trade->getTradeName()?></span><br />
        <span>Trade Description: </span><?= $Trade->getDescription()?></span><br />
        <br />
        <br />
        <br />
        
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>