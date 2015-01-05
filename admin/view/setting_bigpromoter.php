<div class="wrap">
<?php
    $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
    $control->checkUpdate($uptaded);
?>
<form method="post" action="options.php">
    <div class="panel panel-primary">
        <div class="panel-heading">
                <h3 class="panel-title">BigPromoter API Settings</h3>
        </div>
        <div class="panel-body">
            <script>
            //Test API	
                jQuery(document).ready(function () {
                    ajaxAddService("<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/bigpromoter_api_test.php","#divBigpromoterTest");
                });
            </script>
            <div id="divBigpromoterTest" style="display:block; min-height: 52px;">
                <div id="apiStatus" class="alert alert-info">API Status: <strong><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/style/images/ajax-loader.gif" /> Loading...</strong></div>
            </div>        
            <table class="form-table">
            <?php settings_fields( 'p2p_bigpromoter_api' ); ?>
            <?php do_settings_sections( 'p2p_bigpromoter_api' ); ?>
                <tr>
                    <th>Token</th>
                    <td><input type="text" name="p2p_bigpromoter_api" id="p2p_bigpromoter_api" value="<?php echo get_option('p2p_bigpromoter_api'); ?>" class="w100p"/></td>
                </tr>
                <tr>
                    <th>Site</th>
                    <td><input type="text" name="p2p_bigpromoter_site" id="p2p_bigpromoter_site" value="<?php echo get_option('p2p_bigpromoter_site'); ?>" class="w100p"/></td>
                </tr>
            </table>
        </div>
    </div>
    <?php submit_button(); ?>
</form>
</div>