<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

       <h4><?php echo $title ?></h4>
        <table>           
            <tr><td>Trade Name:</td><td><?=$Trade->getTradeName() ?></td></tr>
            <tr><td>Description:</td><td><?=$Trade->getDescription() ?></td></tr>
            <tr><td>Date created</td><td><?=$Trade->getDateCreated() ?></td></tr>
        </table>

        <form method="post" action="<?= WEB_FOLDER.'Trade/Bid'?>">
            <span>Offer Category</span>
            <input type="hidden" value="<?= $Trade->getTradeOID() ?>" name="Trade_OID" />
                <select name="Trade_Category">
                    <!-- Get the categories from the database and render them as select options -->
                    <option value="1">Cars</option>
                    <option value="2">Electronics</option>
                    <option value="3">Clothes</option>
                    <option value="4">TV</option>
                 </select>  
                <br />                
                <input type="submit" value="Submit"><br />
        </form>

        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>