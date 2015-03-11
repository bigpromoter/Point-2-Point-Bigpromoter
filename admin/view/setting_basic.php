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
                    <h3 class="p2p_bp_panel-title">Extra Request</h3>
            </div>
            <div class="p2p_bp_panel-body">
                <table class="form-table">
                    <tr valign="top">
                        <?php $checked = (get_option('extra_requirement')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="extra_requirement" value="1" <?php echo $checked; ?>/> Enable Extra Requirement</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('extra_car_seat')?'checked':''); ?>
                        <td colspan="2"><input type="checkbox" name="extra_car_seat" value="1" <?php echo $checked; ?>/> Car Seat</td>
                        <td style="text-align: right;">Price:</td>
                        <td><?php echo get_option('select_currency'); ?><input type="text" name="extra_car_seat_value" value="<?php echo get_option('extra_car_seat_value'); ?>"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="p2p_bp_panel p2p_bp_panel-primary">
            <div class="p2p_bp_panel-heading">
                    <h3 class="p2p_bp_panel-title">Conflict Panel</h3>
            </div>
            <div class="p2p_bp_panel-body">
                <table class="form-table">
                    <tr valign="top">
                        <td colspan="4">If you are having compatibility problems, try disabling the following functions loaded in this plugin. (Ps. This can result in unexpected behavior.)</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_jquery_ui_datepicker')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_jquery_ui_datepicker" value="1" <?php echo $checked; ?>/> Disable jQuery-ui-datepicker</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_jquery_ui_button')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_jquery_ui_button" value="1" <?php echo $checked; ?>/> Disable jQuery-ui-button</td>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_bootstrap_js')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_bootstrap_js" value="1" <?php echo $checked; ?>/> Disable Bootstrap.js (JavaScript)</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_bootstrap_css')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_bootstrap_css" value="1" <?php echo $checked; ?>/> Disable Bootstrap.css (StyleSheet)</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_font_awesome')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_font_awesome" value="1" <?php echo $checked; ?>/> Disable FontAwesome</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_jquery_ui')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_jquery_ui" value="1" <?php echo $checked; ?>/> Disable jQuery-ui.css (StyleSheet)</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_wp_color_picker')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_wp_color_picker" value="1" <?php echo $checked; ?>/> Disable Wp-color-picker (only admin)</td>
                    </tr>
                    <tr valign="top">
                        <?php $checked = (get_option('p2p_conflict_load_jquery_google')?'checked':''); ?>
                        <td colspan="4"><input type="checkbox" name="p2p_conflict_load_jquery_google" value="1" <?php echo $checked; ?>/> Force to load jQuery from Google Hosted Library (v.2.1.3)</td>
                    </tr>
                </table>
            </div>
        </div>
    <?php submit_button(); ?>
    </form>		
</div>