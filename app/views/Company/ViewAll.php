<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <h4><?php echo $title ?></h4>        
            <hr/>                           
            <?php foreach ($Companies as $company) : ?>
                    <span>Trade Name: <a href="<?= WEB_FOLDER.'Trade/Trade/'.$trade->getTradeOID()?>"><span><?=$trade->getTradeName()?></span><br /></a></span>
                    Description: <span><?=$company->getName()?></span><br />                    
                    Date created: <span><?=$company->getDescription()?></span><br />                  
            <?php endforeach; ?>
            <hr/>       
        
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>