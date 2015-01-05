<div class="wrap">
<?php
    $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
    $control->checkUpdate($uptaded);
?>
<form method="post" action="options.php">
    <div class="panel panel-primary">
        <div class="panel-heading">
                <h3 class="panel-title">E-mail Settings</h3>
        </div>
        <div class="panel-body">
            <table class="form-table">
            <?php settings_fields( 'p2p_bigpromoter_email' ); ?>
            <?php do_settings_sections( 'p2p_bigpromoter_email' ); ?>
                <tr>
                        <td class="w20p">E-mail</td>
                        <td class="w80p"><input type="text" name="p2p_email" id="p2p_email" value="<?php echo get_option('p2p_email'); ?>" class="w100p"/></td>
                </tr>
                <tr>
                        <td class="w20p">Password</td>
                        <td class="w80p"><input type="password" name="p2p_pass" id="p2p_pass" value="<?php echo get_option('p2p_pass'); ?>"/></td>
                </tr>
                <tr>
                        <td class="w20p">SMTP Secure</td>
                        <td class="w80p"><input type="text" name="p2p_smtpsecure" id="p2p_smtpsecure" value="<?php echo get_option('p2p_smtpsecure'); ?>" class="w100""/></td>
                </tr>
                <tr>
                        <td class="w20p">Host</td>
                        <td class="w80p"><input type="text" name="p2p_host" id="p2p_host" value="<?php echo get_option('p2p_host'); ?>" class="w100p""/></td>
                </tr>
                <tr>
                        <td class="w20p">Port</td>
                        <td class="w80p"><input type="text" name="p2p_port" id="p2p_port" value="<?php echo get_option('p2p_port'); ?>"  class="w100"/></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
                <h3 class="panel-title">E-mail Receiver</h3>
        </div>
        <div class="panel-body">            
            <table class="form-table">
                <tr valign="top">
                        <td scope="row">E-mail 1</td>
                        <td colspa="3"><input type="text" name="email_receive_1" value="<?php echo get_option('email_receive_1'); ?>" class="w100p" /></td>
                </tr>
        
                <tr valign="top">
                        <td scope="row">E-mail 2</td>
                        <td colspa="3"><input type="text" name="email_receive_2" value="<?php echo get_option('email_receive_2'); ?>" class="w100p" /></td>
                </tr>
        
                <tr valign="top">
                        <td scope="row">E-mail 3</td>
                        <td colspa="3"><input type="text" name="email_receive_3" value="<?php echo get_option('email_receive_3'); ?>" class="w100p" /></td>
                </tr>
                </table> 		
            </div>
        </div>
    </div>
    <?php submit_button(); ?>
</form>
</div>