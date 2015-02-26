<?php
    wp_enqueue_script('custom-background');
    wp_enqueue_style('wp-color-picker');
?>
<div class="wrap">
<?php
    $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
    $control->checkUpdate($uptaded);
?>
<form method="post" action="options.php">
    <div class="p2p_bp_panel p2p_bp_panel-primary">
        <div class="p2p_bp_panel-heading">
                <h3 class="p2p_bp_panel-title">E-mail Settings</h3>
        </div>
        <div class="p2p_bp_panel-body">
            <table class="form-table">
            <?php settings_fields( 'p2p_bigpromoter_email' ); ?>
            <?php do_settings_sections( 'p2p_bigpromoter_email' ); ?>
                <tr>
                        <td class="w20p">Name</td>
                        <td class="w80p"><input type="text" name="p2p_email_name" id="p2p_email_name" value="<?php echo get_option('p2p_email_name'); ?>" class="w100p"/></td>
                </tr>
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
                        <td class="w80p">
                            <div class="p2p_bp_btn-group" data-toggle="buttons">
                                <label id="no" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (get_option('p2p_smtpsecure') == 'no')?'active':''; ?>">
                                    <input type="radio" name="p2p_smtpsecure" id="no" value='no' <?php echo (get_option('p2p_smtpsecure') == 'no')?'checked':''; ?>> No Secure
                                </label>
                                <label id="paypal" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (get_option('p2p_smtpsecure') == 'ssl')?'active':''; ?>">
                                    <input type="radio" name="p2p_smtpsecure"  id="ssl" value='ssl' <?php echo (get_option('p2p_smtpsecure') == 'ssl')?'checked':''; ?>> SSL
                                </label>
                                <label id="braintree" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (get_option('p2p_smtpsecure') == 'tls')?'active':''; ?>">
                                    <input type="radio" name="p2p_smtpsecure" id="tls" value='tls' <?php echo (get_option('p2p_smtpsecure') == 'tls')?'checked':''; ?>> TLS
                                </label>
                            </div>
                </tr>
                <tr>
                        <td class="w20p">Host</td>
                        <td class="w80p"><input type="text" name="p2p_host" id="p2p_host" value="<?php echo get_option('p2p_host'); ?>" class="w100p""/></td>
                </tr>
                <tr>
                        <td class="w20p">Port</td>
                        <td class="w80p"><input type="text" name="p2p_port" id="p2p_port" value="<?php echo get_option('p2p_port'); ?>"  class="w100"/></td>
                </tr>
                <?php (get_option('p2p_email_admin'))?$checked='checked':$checked=''; ?>
                <tr valign="top">
                    <td scope="row" colspan="2"><input type="checkbox" name="p2p_email_admin" <?php echo $checked; ?>> Send email to WP admin?</td>
                </tr>
                <?php (get_option('p2p_email_debug'))?$checked='checked':$checked=''; ?>
                <tr valign="top">
                    <td scope="row" colspan="2"><input type="checkbox" name="p2p_email_debug" <?php echo $checked; ?>> Debug errors?</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="p2p_bp_panel p2p_bp_panel-primary">
        <div class="p2p_bp_panel-heading">
                <h3 class="p2p_bp_panel-title">E-mail Info</h3>
        </div>
        <div class="p2p_bp_panel-body">            
            <table class="form-table">
                <tr valign="top">
                        <td scope="row">Signature</td>
                        <td colspan="6"><textarea type="text" name="p2p_email_signature" class="w100p" rows="5" cols="80"><?php echo get_option('p2p_email_signature'); ?></textarea></td>
                        <td><small>It's allowed to insert HTML tags.<BR>To open a new line, insert &lt;BR&gt; at end of the line. </small></td>
                </tr>
                <tr valign="top">
                        <td scope="row" colspan="8"><strong>E-mail Title Colors:</strong></td>
                </tr>
        
                <tr valign="top">
                        <td scope="row">Background</td>
                        <td colspan="3"><input type="text" name="p2p_email_color_bg" value="<?php echo get_option('p2p_email_color_bg'); ?>" class=" colorfield"/></td>
                        <td scope="row">Font</td>
                        <td colspan="3"><input type="text" name="p2p_email_color_txt" value="<?php echo get_option('p2p_email_color_txt'); ?>" class=" colorfield"/></td>
                </tr>
            </table> 		
        </div>
    </div>
    <div class="p2p_bp_panel p2p_bp_panel-primary">
        <div class="p2p_bp_panel-heading">
                <h3 class="p2p_bp_panel-title">E-mail Receiver</h3>
        </div>
        <div class="p2p_bp_panel-body">            
            <table class="form-table">
                <tr valign="top">
                        <td scope="row">E-mail 1</td>
                        <td colspan="3"><input type="text" name="email_receive_1" value="<?php echo get_option('email_receive_1'); ?>" class="w100p" /></td>
                        <td scope="row">Name 1</td>
                        <td colspan="3"><input type="text" name="email_receive_name_1" value="<?php echo get_option('email_receive_name_1'); ?>" class="w100p" /></td>
                </tr>
        
                <tr valign="top">
                        <td scope="row">E-mail 2</td>
                        <td colspan="3"><input type="text" name="email_receive_2" value="<?php echo get_option('email_receive_2'); ?>" class="w100p" /></td>
                        <td scope="row">Name 2</td>
                        <td colspan="3"><input type="text" name="email_receive_name_2" value="<?php echo get_option('email_receive_name_2'); ?>" class="w100p" /></td>
                </tr>
        
                <tr valign="top">
                        <td scope="row">E-mail 3</td>
                        <td colspan="3"><input type="text" name="email_receive_3" value="<?php echo get_option('email_receive_3'); ?>" class="w100p" /></td>
                        <td scope="row">Name 3</td>
                        <td colspan="3"><input type="text" name="email_receive_name_3" value="<?php echo get_option('email_receive_name_3'); ?>" class="w100p" /></td>
                </tr>
                </table> 		
            </div>
        </div>
    </div>    
    <?php submit_button(); ?>
</form>
</div>