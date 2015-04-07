<?php include_once(P2P_DIR_INCLUDE.'admin.php'); ?>
<div id="p2p_bp_dashboard">
    <form id="p2p_bp" method="post" action="options.php">
        <?php settings_fields( 'p2p_bigpromoter_services' );?>
        <?php do_settings_sections( 'p2p_bigpromoter_services' );?>
        <?php $control_util->settings(isset($_GET['settings-updated'])?$_GET['settings-updated']:NULL); ?>
            <div id="p2p_bp_header" class="w100p">
                <div class="dash_left"><div class="p2p_bp_title_header"><?php echo P2P_TITLE; ?></div></div>
                <div class="dash_right">
                    <div class="left">
                        <div id='p2p_bp_icon_wrench' class='p2p_bp_header-text p2p_bp_tab' style='display:block;'><i class='fa fa-wrench'></i> <?php _e('Manage Services', P2P_TRANSLATE); ?></div>
                    </div>
                    <div class="right"><button type="submit" name="submit" id="submit" class="p2p_bp_save right"><?php _e('Save Changes', P2P_TRANSLATE); ?></button></div>
                </div>
            </div>
            <div id="p2p_bp_body" class="left ">
                <div id="p2p_bp_loading" class="p2p_bp_tab"><img src="<?php echo P2P_DIR_IMAGES; ?>ajax-loader-2.gif" /></div>
                <div id="p2p_bp_services" class="p2p_bp_tab"  style="display:none;">
                    <div class="serviceTable serviceTop">
                        <div class="colActionS"><?php _e('Action', P2P_TRANSLATE); ?></div>
                        <div class="colServiceS"><?php _e('Service', P2P_TRANSLATE); ?></div>
                    </div>
            <?php
                $service = get_option('p2p_bp_services');
                if (isset($service['name']) && is_array($service['name']) && count($service['name']) > 0) {
                    for ($i = 0; $i < count($service['name']); $i++) {
            ?>
                    <div id="showAjax<?php echo $i; ?>" class="p2p_bp_alert left p2p_bp_hide w90p"></div>
                    <div id="table<?php echo $i; ?>" class="serviceTable">
                        <div class="colActionS">
                            <div id="<?php echo $i; ?>" class="p2p_service_delete p2p_bp_btn p2p_bp_btn_remove" ><i class="fa fa-times"></i></div>
                        </div>
                        <div class="colServiceS">
                            <input id="<?php echo $i; ?>" type="text" name="p2p_bp_services[name][]" value="<?php echo $service['name'][$i]; ?>" class='w100p service_name'/>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
                    <div id="newService"></div>
                    <div id="showAjax1000" class="p2p_bp_alert left p2p_bp_hide w90p"></div>    
                    <div class="serviceTable serviceBottom ">
                        <div class="colActionS"><div id="1000" class="p2p_service_add p2p_bp_btn p2p_bp_btn_add" ><i class="fa fa-plus"></i></div></div>
                        <div class="colServiceS">
                            <input type="text" id="1000" value="" class='w100p service_name' />
                        </div>
                    </div>
                    <div class="p2p_option">
                        <div class="carTable carTop left w100p">
                            <div class="colBody w100p"><?php _e('Service Options', P2P_TRANSLATE); ?></div>
                        </div>
                        <div class="carTable left w100p">
                            <div class="w100p">
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row"><?php _e('Add Other on the list?',P2P_TRANSLATE); ?></th>
                                        <td colspan="3"><?php echo $control_form->createRadioYN("p2p_bp_services_others", get_option('p2p_bp_services_others'), 'services_others'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="p2p_bp_footer" class="left w100p">
                <div class="dash_right">
                    <div class="right"><button type="submit" name="submit" id="submit" class="p2p_bp_save right"><?php _e('Save Changes', P2P_TRANSLATE); ?></button></div>
                </div>
            </div>
        </div>
        <?php echo $control_form->createInput('p2p_bp_last_tab', '', 'hidden'); ?>
    </form>
</div>
<script>
jQuery(function () {
    services();
});
</script>