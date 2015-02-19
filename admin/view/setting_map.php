<?php
    //Get info from ADDRESS
    $attr = array('width'=> get_option('map_width'), 'height' => get_option('map_height'), 'zoom' => get_option('start_map_zoom'));
    $map_info = $control->generateMap($attr,get_option('start_map_address'));
?>
<div class="wrap">
<?php
    $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
    $control->checkUpdate($uptaded);
?>
<form method="post" action="options.php">
    <div class="p2p_bp_panel p2p_bp_panel-primary">
        <div class="p2p_bp_panel-heading">
                <h3 class="p2p_bp_panel-title">Map Settings</h3>
        </div>
        <div class="p2p_bp_panel-body">
            <?php settings_fields( 'p2p_bigpromoter_options' ); ?>
            <?php do_settings_sections( 'p2p_bigpromoter_options' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Google Maps API:</th>
                    <td colspan="3"><input type="text" name="google_api" value="<?php echo get_option('google_api'); ?>" class="w100p"/></td>
                </tr>
            </table>
            <div class="alert p2p_bp_alert-info" role="alert">Coordinates</div>
            <table class="form-table">      
                <tr valign="top">
                <th scope="row">Latitude:</th>
                <td><?php echo $map_info['lat']; ?><input type="hidden" name="start_map_lat" value="<?php echo $map_info['lat']; ?>" /></td>
                <th scope="row">Longitude:</th>
                <td><?php echo $map_info['long']; ?><input type="hidden" name="start_map_lon" value="<?php echo $map_info['long']; ?>" /></td>
                </tr>                
                <tr valign="top">
                <th scope="row">Address:</th>
                <td colspa="3"><input type="text" name="start_map_address" value="<?php echo get_option('start_map_address'); ?>"  class="w100p"/></td>
                </tr>
                
                <tr valign="top">
                <th scope="row">Zoom:</th>
                <td colspan="3"><?php echo $control->selectArray('start_map_zoom', array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20), get_option('start_map_zoom'), 'w100'); ?></td>
                </tr>
                
                <tr valign="top">
                <td colspan="4"><?php echo $map_info['script']; ?></td>
                </tr>
            </table>
            <div class="alert p2p_bp_alert-info" role="alert">Size</div>
            <table class="form-table">
                <tr valign="top">
                <th scope="row">Width:</th>
                <td><input type="text" name="map_width" value="<?php echo get_option('map_width'); ?>"  class="w100p"/></td>
                <th scope="row">Height:</th>
                <td><input type="text" name="map_height" value="<?php echo get_option('map_height'); ?>"  class="w100p"/></td>
                </tr>
            </table>
        </div>
    </div>
    
    <?php submit_button(); ?>

</form>
</div>