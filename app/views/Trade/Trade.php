<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body style="color:#666666;">

        <h4><?php echo $title ?></h4>      
        <table>
            <?php if (isset($Bid_Count)) : ?>
                <tr><span style="color:#00ff00;"><b>Number of bids: &nbsp;<span><?= $Bid_Count ?></b></span></tr>
            <?php endif; ?>
            <!--<tr><td>Trade ID:</td><td><?=$Trade->getTradeOID() ?></td></tr>-->
            <tr><td>Trade Name:</td><td><?=$Trade->getTradeName() ?></td></tr>
            <tr><td>Description:</td><td><?=$Trade->getDescription() ?></td></tr>
            <tr><td>Date created</td><td><?=$Trade->getDateCreated() ?></td></tr>
        </table>       
        
        <?php if(!($Trade->getUserOID() == $_SESSION['user']->UserOID)) 
            // whether user has already placed a bid on the trade or not.
            $Already_bid = false;
            
            foreach ($Trade->getTradeBids() as $Bid)
            {
                if($_SESSION['user']->UserOID == $Bid->getBid_User())
                    $Already_bid = true;
            }
        ?>
        
        <?php if(isset($Already_bid) && ($Already_bid == false)) { ?>
            <form method="POST" action="<?= WEB_FOLDER.'Trade/Bid/0'?>">
                <input type="hidden" value="<?= $Trade->getTradeOID() ?>" name="Trade_OID" id="Trade_OID"/>
                <input type="submit" value="Make a bid"/><br />
            </form>
        <?php } else{ ?>
            <form method="POST" action="<?= WEB_FOLDER.'Trade/Bid/1'?>">
                <input type="hidden" value="<?= $Trade->getTradeOID() ?>" name="Trade_OID" id="Trade_OID"/>
                <span style="color:red;"><b>Already made a bid</b></span><br />
                <input type="submit" value="Change my bid offer"/><br />
            </form>
        <?php } ?>

        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>