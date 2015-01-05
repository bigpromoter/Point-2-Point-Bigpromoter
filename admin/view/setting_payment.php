<div class="wrap">
<?php
    $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
    $control->checkUpdate($uptaded);
?>
<form method="post" action="options.php">
<?php settings_fields( 'p2p_bigpromoter_payment' );?>
<?php do_settings_sections( 'p2p_bigpromoter_payment' );?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                    <h3 class="panel-title">Payment Settings</h3>
            </div>
            <div class="panel-body">    
                <?php
                    //Check with payment is activated
                    $payment = $control->paymentType(get_option('p2p_payment_type'));
                ?>
                <div class="btn-group" data-toggle="buttons">
                    <label id="no" class="btn btn-primary <?php echo $payment['active_no']; ?>">
                        <input type="radio" name="p2p_payment_type" id="no" value='no' <?php echo $payment['checked_no']; ?>> No Payment
                    </label>
                    <label id="paypal" class="btn btn-primary <?php echo $payment['active_paypal']; ?>">
                        <input type="radio" name="p2p_payment_type"  id="paypal" value='paypal' <?php echo $payment['checked_paypal']; ?>> PayPal
                    </label>
                    <label id="braintree" class="btn btn-primary <?php echo $payment['active_braintree']; ?>">
                        <input type="radio" name="p2p_payment_type" id="braintree" value='braintree' <?php echo $payment['checked_braintree']; ?>> BrainTree
                    </label>
                </div>
                <script>
                    jQuery('#no').click(function() {
                        jQuery('#divBrainTree').css('display','none');
                        jQuery('#divPayPal').css('display','none');
                    });
                    jQuery('#paypal').click(function() {
                        jQuery('#divBrainTree').css('display','none');
                        jQuery('#divPayPal').css('display','block');
                    });
                    jQuery('#braintree').click(function() {
                        jQuery('#divBrainTree').css('display','block');
                        jQuery('#divPayPal').css('display','none');
                    });
                </script>
                <div id="divBrainTree" style="display: <?php echo $payment['display_braintree']; ?>;">
                    <h3>BrainTree</h3>
                    <table class="form-table">
                <?php
                        (get_option('p2p_braintree_enviroment')=='sandbox')?$checked_sandbox='checked':$checked_sandbox='';
                        (get_option('p2p_braintree_enviroment')=='production')?$checked_production='checked':$checked_production='';
                ?>        
                        <tr valign="top">
                            <th scope="row">Enviroment</th>
                            <td colspan="3"><input type="radio" id="sandbox" name="p2p_braintree_enviroment" value="sandbox" <?php echo $checked_sandbox; ?>> SandBox | <input type="radio" id="production" name="p2p_braintree_enviroment" value="production" <?php echo $checked_production; ?>>Production            </td>
                        </tr>		
                        <tr valign="top">
                            <th scope="row">Merchant ID</th>
                            <td colspan="3"><input type="text" name="p2p_braintree_merchantId" value="<?php echo get_option('p2p_braintree_merchantId'); ?>"  class="w100p"/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Public Key</th>
                            <td colspan="3"><input type="text" name="p2p_braintree_publicKey" value="<?php echo get_option('p2p_braintree_publicKey'); ?>"  class="w100p"/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Private Key</th>
                            <td colspan="3"><input type="text" name="p2p_braintree_privateKey" value="<?php echo get_option('p2p_braintree_privateKey'); ?>" class="w100p" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Configuration Code</th>
                            <td colspan="3"><textarea name="p2p_braintree_config_code" class="w100p" rows="5"><?php echo get_option('p2p_braintree_config_code'); ?></textarea></td>
                        </tr>
                    </table>
                </div>
                <div id="divPayPal" style="display: <?php echo $payment['display_paypal']; ?>;">
                    <h3>PayPal</h3>
                    <table class="form-table">
                <?php
                        (get_option('p2p_paypal_enviroment')=='sandbox')?$checked_sandbox='checked':$checked_sandbox='';
                        (get_option('p2p_paypal_enviroment')=='production')?$checked_production='checked':$checked_production='';
                ?>        
                        <tr valign="top">
                            <th scope="row">Enviroment</th>
                            <td colspan="3"><input type="radio" id="sandbox" name="p2p_paypal_enviroment" value="sandbox" <?php echo $checked_sandbox; ?>> SandBox | <input type="radio" id="production" name="p2p_paypal_enviroment" value="production" <?php echo $checked_production; ?>>Production            </td>
                        </tr>		
                        
                        <tr valign="top">
                            <th scope="row">Client ID</th>
                            <td colspan="3"><input type="text" name="p2p_paypal_client_ID" value="<?php echo get_option('p2p_paypal_client_ID'); ?>"  class="w100p"/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Secret</th>
                            <td colspan="3"><input type="text" name="p2p_paypal_secret" value="<?php echo get_option('p2p_paypal_secret'); ?>"  class="w100p"/></td>
                        </tr>
                    </table>    
                </div>
            </div>
        </div>
        <?php submit_button(); ?>
    </form>
</div>