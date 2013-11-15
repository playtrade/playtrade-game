<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <h4><?php echo $title ?></h4> 
                               
            <?php foreach ($Trades as $trade) : ?>  
                
                <hr/>        
                
                <div id="trade_content">        
                    
                    <b><span>Trade Name: <a href="<?= WEB_FOLDER.'Trade/Trade/'.$trade->getTradeOID()?>"><span><?=$trade->getTradeName()?></span></a></span></b><br />
                    <b>Number of bids: <span style="color:green"><?= count($trade->getTradeBids())?></span></b><br/>
                    Description: <span><?=$trade->getDescription()?></span><br />                    
                    Date created: <span><?=$trade->getDateCreated()?></span><br />
                    Time created: <span><?=$trade->getTimeCreated()?></span><br />
                    
                    
                    <?php 
                                // Set status to readable test not numbers
                                switch ($trade->getStatus()){
                                    case 0: $Status = "<span style='color:#00ff00;'>Active.</span>";break;
                                    case 1: $Status = "<span style='color:#ffff00;'>Pedding.</span>";break;   
                                    case 2: $Status = "<span style='color:#0000ff;'>Completed.</span>";break;                                                                                                                    break;
                                    case 3: $Status = "<span style='color:#ff0000;'>Deleted.</span>";break;
                                    default : $Status = "Unknown Status - Contact administrator.";
                                }
                    ?>
                    
                    Status: <span><b><?= $Status ?></b></span><br />
                    
                </div>
                
                <hr/>
                <br/>
                
            <?php endforeach; ?>
      
        <br />
        
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>