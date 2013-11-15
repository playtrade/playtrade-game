<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <h4><?php echo $title ?></h4>  
        <form action="<?php echo WEB_FOLDER; ?>Company/AddCompany" method="post"> 
             <table>
                <tr><td>Company Name</td><td><input type="text" name="CompanyName" id="CompanyName"></td></tr>
                <tr><td>Company Description</td><td><input type="text" name="CompanyDescription" id="CompanyDescription"></td></tr>
                <td><input type="submit" name="submit" value="submit"></td>
            </table>
        <br />
        </form>
        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>