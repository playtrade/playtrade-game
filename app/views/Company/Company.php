<html>
    <?php echo mxitViewHelper::getHeadTags(); ?>
    <body <?php echo mxitViewHelper::getBodyTags(); ?> >

        <h4><?php echo $title ?></h4>
        
        <table>
            <tr><td>Company ID:</td><td><?=$company->Company_OID ?></td></tr>
            <tr><td>Company Name:</td><td><?=$company->Name ?></td></tr>
            <tr><td>Company Description:</td><td><?=$company->Description ?></td></tr>
        </table>

        <?php trackingHelper::trackGoogleAnalytics($pageTitle); ?>

    </body>

</html>