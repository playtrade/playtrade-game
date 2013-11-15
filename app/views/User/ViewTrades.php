<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <h4><?php echo $title ?></h4><br/>
       
                               
            <?php foreach ($Trades as $trade) : ?>  
                
                <hr/>        
                
                <div id="trade_content">        
                    
                    <b><span>Trade Name: <a href="<?= WEB_FOLDER.'Trade/Trade/'.$trade->getTradeOID()?>"><span><?=$trade->getTradeName()?></span><br /></a></span></b>
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
                    <?php if(($trade->getStatus() == 0 ) && ($trade->getUserOID()== $_SESSION['user']->UserOID)) : 
                            foreach ($trade->getTradeBids() as $bid) :  ?>
                                <span>Bid:</span>Category: <?= $bid->getBid_User() ?> &nbsp;
                                <input type="submit" value="Accept Bid" name="btn_accept_bid">&nbsp;<input type="submit" value="Reject bid" name="btn_reject_bid"/>
                        
                            <?php endforeach; ?>
                    <?php endif;?>
                </div>               
                Number of bids: <span style="color:red"><?= $trade->getNumberOfBids() ?></span>
                <hr/>
                <br/>
                
            <?php endforeach; ?>
      
        <br />
        
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>