<?php include_once(P2P_DIR_INCLUDE.'admin.php'); ?>
<div id="p2p_bp_dashboard">
    <div id="p2p_bp_header" class="w100p">
        <div class="dash_left"><div class="p2p_bp_title_header"><?php echo P2P_TITLE; ?></div></div>
        <div class="dash_right">
            <div class="left">
                <div id='p2p_bp_icon_wrench' class='p2p_bp_header-text p2p_bp_tab' style='display:block;'><i class='fa fa-files-o'></i> <?php _e('Export/Import', P2P_TRANSLATE); ?></div>
            </div>
            <div class="right"></div>
        </div>
    </div>
    <div id="p2p_bp_body" class="left w100p">
        <div id="p2p_bp_loading" class="p2p_bp_tab"><img src="<?php echo P2P_DIR_IMAGES; ?>ajax-loader-2.gif" /></div>
        <div id="p2p_bp_export_import" class="p2p_bp_tab" style="display:none;">
            <div class="p2p_bp_radio">
                <input type="radio" name="import_export" id="p2p_bp_export" value='export' checked> 
                    <label for="p2p_bp_export"><?php _e('Export', P2P_TRANSLATE); ?></label>
                
                <input type="radio" name="import_export"  id="p2p_bp_import" value='import'> 
                    <label for="p2p_bp_import"><?php _e('Import', P2P_TRANSLATE); ?></label>
            </div>
            <div id="p2p_bp_export_div" class="p2p_bp_exp_imp">
                <table class="p2p_bp_table">
                    <tr>
                        <td><?php _e('Options', P2P_TRANSLATE); ?></td>
                        <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp_export_options', 1, 'p2p_bp_export_options'); ?> </td>
                    </tr>
                    <tr>
                        <td><?php _e('Fleet', P2P_TRANSLATE); ?></td>
                        <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp_export_fleet', 1,'p2p_bp_export_fleet'); ?></td>
                    </tr>                        
                    <tr>
                        <td><?php _e('Service', P2P_TRANSLATE); ?></td>
                        <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp_export_service', 1,'p2p_bp_export_service'); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"><div id="p2p_bp_export_create" name="p2p_bp_export_create" class="p2p_bp_save left"><?php _e('Create Export Code', P2P_TRANSLATE); ?></div></td>
                    </tr>
                    <tr>
                        <td colspan="4"><div id="p2p_bp_export_code"></div></td>
                    </tr>
                </table>
            </div>
            <div id="p2p_bp_import_div" class="p2p_bp_exp_imp" style='display:none;'>
                <table class="p2p_bp_table">
                    <tr>
                        <td colspan="4"><div class="p2p_bp_alert p2p_bp_info"><?php _e('Paste your code below!',P2P_TRANSLATE); ?></div></td>
                    </tr>
                    <tr>
                        <td colspan="4"><textarea style="width: 100%; height:200px;" id="p2p_bp_import_textarea"></textarea></td>
                    </tr>                        
                    <tr>
                        <td colspan="4"><div id="p2p_bp_import_result"></div></td>
                    </tr>
                    <tr>
                        <td colspan="4"><div id="p2p_bp_import_create" name="p2p_bp_import_create" class="p2p_bp_save left"><?php _e('Import Code', P2P_TRANSLATE); ?></div></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="p2p_bp_footer" class="left w100p">
        </div>
    </div>
</div>
<script>
jQuery(function () {
    exportImport();
});
</script>