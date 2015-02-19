<div class="wrap">
<?php
    $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
    $control->checkUpdate($uptaded);
?>
    <form method="post" action="options.php">
        <div class="p2p_bp_panel p2p_bp_panel-primary">
            <div class="p2p_bp_panel-heading">
                    <h3 class="p2p_bp_panel-title">Basic Settings</h3>
            </div>
            <div class="p2p_bp_panel-body">
                <table class="form-table">
                <?php settings_fields( 'p2p_bigpromoter_main' );?>
                <?php do_settings_sections( 'p2p_bigpromoter_main' );?>
                    <tr valign="top">
                        <th scope="row">Reservation Page:</th>
                        <td colspan="3"><input type="text" name="reservation_page" value="<?php echo get_option('reservation_page'); ?>"  class="w100p"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Select Distance:</th>
                        <td colspan="3">
                        <?php echo $control->selectArray('select_distance', array('kms','miles'), get_option('select_distance'), 'w100'); ?>			
                        </td>
                    </tr>
                
                    <tr valign="top">
                        <th scope="row">Breakdown Distance:</th>
                        <td colspan="3"><input type="text" name="distance" id="distance" value="<?php echo get_option('distance'); ?>"  class='w100'/> </td>
                    </tr>			
                
                    <tr valign="top">
                        <th scope="row">Currency:</th>
                        <td colspan="3">
                        <?php echo $control->selectArray('select_currency', array('$'), get_option('select_currency'), 'w100'); ?>							
                        </td>
                    </tr>
                    <?php (get_option('insert_gratuity'))?$checked='checked':$checked=''; ?>
                    <tr valign="top">
                        <th scope="row" colspan="4"><input type="checkbox" name="insert_gratuity" <?php echo $checked; ?>> Insert field to user fill gratuity on Reservation?</th>
                    </tr>
                    <?php (get_option('placeholder'))?$checked='checked':$checked=''; ?>
                    <tr valign="top">
                        <th scope="row" colspan="4"><input type="checkbox" name="placeholder" <?php echo $checked; ?>> Use Placeholder on Input field!</th>
                    </tr>		
                </table>
            </div>
        </div>
        <div class="p2p_bp_panel p2p_bp_panel-primary">
            <div class="p2p_bp_panel-heading">
                    <h3 class="p2p_bp_panel-title">Company Info</h3>
            </div>
            <div class="p2p_bp_panel-body">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Name:</th>
                        <td colspan="3"><input type="text" name="p2p_company_name" value="<?php echo get_option('p2p_company_name'); ?>"  class="w100p"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Phone:</th>
                        <td colspan="3"><input type="text" name="p2p_company_phone" value="<?php echo get_option('p2p_company_phone'); ?>"  class="w100p"/></td>
                    </tr>
                
                    <tr valign="top">
                        <th scope="row">Responsible:</th>
                        <td colspan="3"><input type="text" name="p2p_company_owner" value="<?php echo get_option('p2p_company_owner'); ?>"  class="w100p"/></td>
                    </tr>
                </table>
            </div>
        </div>
    <?php submit_button(); ?>
    </form>		
</div>