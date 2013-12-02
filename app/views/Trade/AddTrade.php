<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >
        
        <h4><?php echo $title ?></h4>
        
        <form action="<?php echo WEB_FOLDER; ?>Trade/AddTrade" method="post">
            <table>                
                <tr><td>Trade Name</td><td><input type="text" name="TradeName" /></td></tr>
                <tr><td>Trade Description</td><td><input type="text" name="TradeDesciption"/></td></tr>
            
                <!-- Specify category for which the item fall under. -->
                 <tr><td>Item Category</td>
                    <td>
                        <select name="ItemCategory">
                        <!-- Get the categories from the database and render them as select options -->
                            <option value="1">Cars</option>
                            <option value="2">Electronics</option>
                            <option value="3">Clothes</option>
                            <option value="4">TV</option>
                        </select>
                    </td>
                </tr> 
                
                <tr>
                    <!-- Specify category you wish to trade the item for -->
                    <td>Trade for:</td>
                    <td>
                        <select name="TradeCategory">
                         <!-- Get the categories from the database and render them as select options -->
                            <option value="1">Cars</option>
                            <option value="2">Electronics</option>
                            <option value="3">Clothes</option>
                            <option value="4">TV</option>
                        </select>
                    </td>
                </tr>
                <tr><td><input type="submit" name="submit" name="submit" value="Submit"></td></tr>
            </table>         
            </form>

        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>