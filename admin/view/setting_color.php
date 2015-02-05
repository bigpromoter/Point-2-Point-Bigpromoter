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
                <h3 class="p2p_bp_panel-title">Color Settings</h3>
        </div>
        <div class="p2p_bp_panel-body">
            <?php settings_fields( 'p2p_bigpromoter_color' ); ?>
            <?php do_settings_sections( 'p2p_bigpromoter_color' ); ?>
            <table class="form-table">      
                <tr valign="top">
                    <th scope="row">Use Custom Colors:</th>
                    <td colspan="3">
                        <div class="p2p_bp_btn-group" data-toggle="buttons">
                            <?php $colorActive = $control->borderColor(get_option('p2p_color'),'color'); ?>
                            <label id="color-yes" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($colorActive['active_yes_color']))?$colorActive['active_yes_color']:''; ?>">
                                <input type="radio" name="p2p_color" id="color_yes_input" value='1' <?php echo (isset($colorActive['checked_yes_color']))?$colorActive['checked_yes_color']:''; ?>> Yes
                            </label>
                            <label id="color-no" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($colorActive['active_no_color']))?$colorActive['active_no_color']:''; ?>">
                                <input type="radio" name="p2p_color"  id="color_no_input" value='0' <?php echo (isset($colorActive['checked_no_color']))?$colorActive['checked_no_color']:''; ?>> No
                            </label>
                        </div>
                        <script>
                            jQuery('#color-yes').click(function() {
                                jQuery('#color_active').css('display','block');
                            });
                            jQuery('#color-no').click(function() {
                                jQuery('#color_active').css('display','none');
                            });
                        </script>
                    </td>
                </tr>
            </table>
            <div id="color_active" style="display: <?php echo (isset($colorActive['display_color']))?$colorActive['display_color']:''; ?>">
                <div class="alert p2p_bp_alert-info" role="alert">Label</div>
                <table class="form-table">      
                    <tr valign="top">
                        <th scope="row">Font:</th>
                        <td colspan="3"><input type="text" name="p2p_label_color" value="<?php echo get_option('p2p_label_color'); ?>" class=" colorfield"/></td>
                    </tr>
                    <tr valign="top">                
                        <th scope="row">Background:</th>
                        <td colspan="3"><input type="text" name="p2p_label_background" value="<?php echo get_option('p2p_label_background') ?>"  class=" colorfield"/></td>
                    </tr>                
                    <tr valign="top">
                        <th scope="row">Border:</th>
                        <td colspan="3">
                            <div class="p2p_bp_btn-group" data-toggle="buttons">
                                <?php $labelBorder = $control->borderColor(get_option('p2p_label_border'),'label'); ?>
                                <label id="label-yes" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($labelBorder['active_yes_label']))?$labelBorder['active_yes_label']:''; ?>">
                                    <input type="radio" name="p2p_label_border" id="label_yes_input" value='1' <?php echo (isset($labelBorder['checked_yes_label']))?$labelBorder['checked_yes_label']:''; ?>> Yes
                                </label>
                                <label id="label-no" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($labelBorder['active_no_label']))?$labelBorder['active_no_label']:''; ?>">
                                    <input type="radio" name="p2p_label_border"  id="label_no_input" value='0' <?php echo (isset($labelBorder['checked_no_label']))?$labelBorder['checked_no_label']:''; ?>> No
                                </label>
                            </div>
                            <script>
                                jQuery('#label-yes').click(function() {
                                    jQuery('#label_border_color').css('display','block');
                                });
                                jQuery('#label-no').click(function() {
                                    jQuery('#label_border_color').css('display','none');
                                });
                            </script>
                            <div id="label_border_color" style="display: <?php echo (isset($labelBorder['display_label']))?$labelBorder['display_label']:''; ?>; margin-top:10px;"><input type="text" id="p2p_label_border_color" name="p2p_label_border_color" value="<?php echo get_option('p2p_label_border_color'); ?>"  class="colorfield"/></div>
                        </td>
                    </tr>
                </table>
                <div class="alert p2p_bp_alert-info" role="alert">Input</div>
                <table class="form-table">      
                    <tr valign="top">
                        <th scope="row">Font:</th>
                        <td colspan="3"><input type="text" name="p2p_input_color" value="<?php echo get_option('p2p_input_color'); ?>" class=" colorfield"/></td>
                    </tr>
                    <tr valign="top">                
                        <th scope="row">Background:</th>
                        <td colspan="3"><input type="text" name="p2p_input_background" value="<?php echo get_option('p2p_input_background') ?>"  class=" colorfield"/></td>
                    </tr>                
                    <tr valign="top">
                        <th scope="row">Border:</th>
                        <td colspan="3">
                            <div class="p2p_bp_btn-group" data-toggle="buttons">
                                <?php $inputBorder = $control->borderColor(get_option('p2p_input_border'),'input'); ?>
                                <label id="input-yes" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($inputBorder['active_yes_input']))?$inputBorder['active_yes_input']:''; ?>">
                                    <input type="radio" name="p2p_input_border" id="input_yes_input" value='1' <?php echo (isset($inputBorder['checked_yes_input']))?$inputBorder['checked_yes_input']:''; ?>> Yes
                                </label>
                                <label id="input-no" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($inputBorder['active_no_input']))?$inputBorder['active_no_input']:''; ?>">
                                    <input type="radio" name="p2p_input_border"  id="input_no_input" value='0' <?php echo (isset($inputBorder['checked_no_input']))?$inputBorder['checked_no_input']:''; ?>> No
                                </label>
                            </div>
                            <script>
                                jQuery('#input-yes').click(function() {
                                    jQuery('#input_border_color').css('display','block');
                                });
                                jQuery('#input-no').click(function() {
                                    jQuery('#input_border_color').css('display','none');
                                });
                            </script>
                            <div id="input_border_color" style="display: <?php echo (isset($inputBorder['display_input']))?$inputBorder['display_input']:''; ?>; margin-top:10px;"><input type="text" id="p2p_input_border_color" name="p2p_input_border_color" value="<?php echo get_option('p2p_input_border_color'); ?>"  class="colorfield"/></div>
                        </td>
                    </tr>
                </table>
                <div class="alert p2p_bp_alert-info" role="alert">Button</div>
                <table class="form-table">      
                    <tr valign="top">
                        <th scope="row">Font:</th>
                        <td colspan="3"><input type="text" name="p2p_button_color" value="<?php echo get_option('p2p_button_color'); ?>" class=" colorfield"/></td>
                    </tr>
                    <tr valign="top">                
                        <th scope="row">Background:</th>
                        <td colspan="3"><input type="text" name="p2p_button_background" value="<?php echo get_option('p2p_button_background') ?>"  class=" colorfield"/></td>
                    </tr>                
                    <tr valign="top">
                        <th scope="row">Border:</th>
                        <td colspan="3">
                            <div class="p2p_bp_btn-group" data-toggle="buttons">
                                <?php $buttonBorder = $control->borderColor(get_option('p2p_button_border'),'button'); ?>
                                <label id="button-yes" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($buttonBorder['active_yes_button']))?$buttonBorder['active_yes_button']:''; ?>">
                                    <input type="radio" name="p2p_button_border" id="button_yes_input" value='1' <?php echo (isset($buttonBorder['checked_yes_button']))?$buttonBorder['checked_yes_button']:''; ?>> Yes
                                </label>
                                <label id="button-no" class="p2p_bp_btn p2p_bp_btn-primary <?php echo (isset($buttonBorder['active_no_button']))?$buttonBorder['active_no_button']:''; ?>">
                                    <input type="radio" name="p2p_button_border"  id="button_no_input" value='0' <?php echo (isset($buttonBorder['checked_no_button']))?$buttonBorder['checked_no_button']:''; ?>> No
                                </label>
                            </div>
                            <script>
                                jQuery('#button-yes').click(function() {
                                    jQuery('#button_border_color').css('display','block');
                                });
                                jQuery('#button-no').click(function() {
                                    jQuery('#button_border_color').css('display','none');
                                });
                            </script>
                            <div id="button_border_color" style="display: <?php echo (isset($buttonBorder['display_button']))?$buttonBorder['display_button']:''; ?>;  margin-top:10px;"><input type="text" id="p2p_button_border_color" name="p2p_button_border_color" value="<?php echo get_option('p2p_button_border_color'); ?>"  class="colorfield"/></div>
                        </td>
                    </tr>
                </table>                
            </div>
        </div>
    </div>
        <div class="p2p_bp_panel p2p_bp_panel-primary w100p pull-left">
        <div class="p2p_bp_panel-heading">
                <h3 class="p2p_bp_panel-title">Custom CSS</h3>
        </div>
        <div class="p2p_bp_panel-body">
            <div class="p2p_bp_col-sm-8"><textarea id="p2p_custom_css" name="p2p_custom_css" class="w100p" rows="10"><?php echo get_option('p2p_custom_css'); ?></textarea></div>
            <div class="p2p_bp_col-sm-3">Paste your CSS code, do not include any tags or HTML in this field. Any custom CSS entered here will override your custom CSS. In some cases, the <i>!important</i> tag may be needed.</div>
        </div>
    </div>
    <?php submit_button(); ?>

</form>
</div>