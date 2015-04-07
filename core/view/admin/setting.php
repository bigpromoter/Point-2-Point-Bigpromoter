<?php include_once(P2P_DIR_INCLUDE.'admin.php'); ?>
<?php
    //Load Script/Style to use Color Picker on Custom Style
    wp_enqueue_script('custom-background');
    wp_enqueue_style('wp-color-picker');
?>
<?php

//All Settings
    $p2p_bp = get_option('p2p_bp');
//All Tabs
    $options = array(
        array('basic',__('basic', P2P_TRANSLATE), 'car'),
        array('extra',__('extra', P2P_TRANSLATE), 'cube'),
        array('map',__('map', P2P_TRANSLATE), 'map-marker'),
        array('calendar',__('calendar', P2P_TRANSLATE), 'calendar'),
        array('payment',__('payment', P2P_TRANSLATE), 'money'),
        array('email',__('email', P2P_TRANSLATE), 'envelope-o'),
        array('style', __('custom style', P2P_TRANSLATE), 'paint-brush'),
        array('conflict',__('conflict', P2P_TRANSLATE), 'unlink'),
        array('api',__('api', P2P_TRANSLATE), 'gear'),
        array('advanced',__('advanced', P2P_TRANSLATE), 'wrench')
    );

?>
<div id="p2p_bp_dashboard">
    <form id="p2p_bp" method="post" action="options.php">
        <?php settings_fields( 'p2p_bigpromoter_main' );?>
        <?php do_settings_sections( 'p2p_bigpromoter_main' );?>
        <?php $control_util->settings(isset($_GET['settings-updated'])?$_GET['settings-updated']:NULL); ?>
        <div id="p2p_bp_header" class="w100p">
            <div class="dash_left"><div class="p2p_bp_title_header"><?php echo P2P_TITLE; ?></div></div>
            <div class="dash_right">
                <div class="left">
                <?php
                foreach ($options as $opt) {
                    $id = $opt[0];
                    $text = ucwords($opt[1]);
                    $icon = $opt[2];
                    $active = (get_option('p2p_bp_last_tab') === 'p2p_bp_'.$id)?'block':'none';
                    echo "<div id='p2p_bp_icon_{$id}' class='p2p_bp_header-text p2p_bp_tab' style='display:{$active};'><i class='fa fa-{$icon}'></i> {$text}</div>";
                } ?>
                </div>
                <div class="right"><button type="submit" name="submit" id="submit" class="p2p_bp_save right"><?php _e('Save Changes', P2P_TRANSLATE); ?></button></div>
            </div>
        </div>
        <div id="p2p_bp_body" class="left w100p">
            <div id="p2p_bp_menu" class="dash_left">
                <ul>
                    <?php
                        foreach ($options as $opt) {
                            $id = $opt[0];
                            $text = ucwords($opt[1]);
                            $icon = $opt[2];
                            $active = (get_option('p2p_bp_last_tab') === 'p2p_bp_'.$id)?'p2p_bp_tab_active':'';
                            echo "<li id='{$id}' class='p2p_bp_choose_tab {$active}'>
                                    <span class='pLeft10'><i class='fa fa-{$icon} fa-2x p2p_bp_icon_menu fcenter'></i> <span class='p2p_bp_list'>{$text}</span></span>
                                </li>";
                        }
                    ?>
                </ul>
            </div>
            <div id="p2p_bp_settings" class="dash_right">
                <div id="p2p_bp_loading" class="p2p_bp_tab"><img src="<?php echo P2P_DIR_IMAGES; ?>ajax-loader-2.gif" /></div>
                <div id="p2p_bp_basic" class="p2p_bp_tab" style="display: none;">
                    <?php $basic = $p2p_bp['basic']; ?>
                    <div>
                        <table class="p2p_bp_table">
                            <tr valign="top">
                                <th scope="row"><?php _e('Reservation Page', P2P_TRANSLATE); ?>:</th>
                                <td><?php echo $control_form->createInput("p2p_bp[basic][reservation_page]",$basic['reservation_page'],"w100p"); ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Select Distance', P2P_TRANSLATE); ?>:</th>
                                <td> <?php echo $control_form->selectArray('p2p_bp[basic][select_distance]', array('kms','miles'), $basic['select_distance'], 'w100'); ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Breakdown Distance', P2P_TRANSLATE); ?>:</th>
                                <td><?php echo $control_form->createInput("p2p_bp[basic][distance]",$basic['distance'],"w100p"); ?></td>
                            </tr>			
                            <tr valign="top">
                                <th scope="row"><?php _e('Currency', P2P_TRANSLATE); ?>:</th>
                                <td><?php echo $control_form->selectArray('p2p_bp[basic][select_currency]', array('$'), $basic['select_currency'], 'w100'); ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Insert field to user fill gratuity on Reservation?', P2P_TRANSLATE); ?></th>
                                <td><?php echo $control_form->createRadioYN('p2p_bp[basic][insert_gratuity]', $basic['insert_gratuity'], 'insert_gratuity'); ?></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Use Placeholder on Input field!', P2P_TRANSLATE); ?></th>
                                <td><?php echo $control_form->createRadioYN('p2p_bp[basic][placeholder]', $basic['placeholder'], 'placeholder'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="p2p_bp_extra" class="p2p_bp_tab" style="display: none;">
                    <?php $extra = $p2p_bp['extra']; ?>
                    <div>
                        <table class="p2p_bp_table">
                            <tr valign="top">
                                <td><?php _e('Enable Extra Requirement', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[extra][enabled]', $extra['enabled'], 'extra_enabled'); ?></td>
                            </tr>
                            <tr valign="top">
                                <td><?php _e('Car Seat', P2P_TRANSLATE); ?></td>
                                <td><?php echo $control_form->createRadioYN('p2p_bp[extra][car_seat]', $extra['car_seat'], 'car_seat'); ?></td>
                                <td style="text-align: right;"><?php _e('Price', P2P_TRANSLATE); ?>:</td>
                                <td><?php echo $basic['select_currency'].$control_form->createInput("p2p_bp[extra][car_seat_value]", $extra['car_seat_value']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="p2p_bp_conflict" class="p2p_bp_tab" style="display: none;">
                    <?php $conflict = $p2p_bp['conflict']; ?>
                    <div>
                        <table class="p2p_bp_table">
                            <tr valign="top">
                                <td colspan="4"><?php _e('If you are having compatibility problems, try disabling the following functions loaded in this plugin. (Ps. This can result in unexpected behavior.)', P2P_TRANSLATE); ?></td>
                            </tr> 
                            <tr valign="top">
                                <td><?php _e('Disable jQuery-ui-datepicker', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[conflict][jquery_ui_datepicker]', $conflict['jquery_ui_datepicker'], 'jquery_ui_datepicker'); ?></td>
                            </tr> 
                            <tr valign="top">
                                <td><?php _e('Disable jQuery-ui-button', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[conflict][jquery_ui_button]', $conflict['jquery_ui_button'],'jquery_ui_button'); ?></td>
                            </tr> 
                            <tr valign="top">
                                <td><?php _e('Disable FontAwesome', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[conflict][font_awesome]', $conflict['font_awesome'],'font_awesome'); ?></td>
                            </tr> 
                            <tr valign="top">
                                <td><?php _e('Disable jQuery-ui.css (StyleSheet)', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[conflict][jquery_ui]', $conflict['jquery_ui'],'jquery_ui'); ?></td>
                            </tr> 
                            <tr valign="top">
                                <td><?php _e('Disable Wp-color-picker (only admin)', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[conflict][wp_color_picker]', $conflict['wp_color_picker'],'wp_color_picker'); ?></td>
                            </tr>
                            <tr valign="top">
                                <td><?php _e('Force to load jQuery from Google Hosted Library (v.2.1.3)', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[conflict][load_jquery_google]', $conflict['load_jquery_google'],'load_jquery_google'); ?></td>
                            </tr> 
                        </table>
                    </div>
                </div>
                <div id="p2p_bp_map" class="p2p_bp_tab" style="display: none;">
                    <?php $map = $p2p_bp['map']; ?>
                    <?php
                        //Get info from ADDRESS
                        $attr = array('width'=> $map['width'], 'height' => $map['height'], 'zoom' => $map['zoom']);
                        $map_info = $control_map->generateMap($attr, $map['address']);
                    ?>
                    <div>
                        <table class="p2p_bp_table">
                            <tr valign="top">
                                <th scope="row"><?php _e('Google Maps API', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->createInput("p2p_bp[map][google_api]", $map['google_api'], "w100p"); ?></td>
                            </tr>
                            <tr valign="top">
                                <td colspan="4"><h4 class="p2p_bp_subtitle"><?php _e('Coordinates', P2P_TRANSLATE); ?></h4></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Latitude', P2P_TRANSLATE); ?>:</th>
                                <td><?php echo $map_info['lat']; ?><input type="hidden" name="p2p_bp[map][start_lat]" value="<?php echo $map_info['lat']; ?>" /></td>
                                <th scope="row"><?php _e('Longitude', P2P_TRANSLATE); ?>:</th>
                                <td><?php echo $map_info['long']; ?><input type="hidden" name="p2p_bp[map][start_lon]" value="<?php echo $map_info['long']; ?>" /></td>
                            </tr>                
                            <tr valign="top">
                                <th scope="row"><?php _e('Address', P2P_TRANSLATE); ?>:</th>
                                <td colspa="3"><?php echo $control_form->createInput("p2p_bp[map][address]", $map['address'], "w100p"); ?></td>
                            </tr>
                            
                            <tr valign="top">
                                <th scope="row"><?php _e('Zoom', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->selectArray('p2p_bp[map][zoom]', array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20), $map['zoom'], 'w100'); ?></td>
                            </tr>
                            <tr valign="top">
                                <td colspan="4"><?php echo $map_info['script']; ?></td>
                            </tr>
                            <tr valign="top">
                                <td colspan="4"><h4 class="p2p_bp_subtitle"><?php _e('Size', P2P_TRANSLATE); ?></h4></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e('Width', P2P_TRANSLATE); ?>:</th>
                                <td><?php echo $control_form->createInput("p2p_bp[map][width]", $map['width'], "w100p"); ?></td>
                                <th scope="row"><?php _e('Height', P2P_TRANSLATE); ?>:</th>
                                <td><?php echo $control_form->createInput("p2p_bp[map][height]", $map['height'], "w100p"); ?></td>
                            </tr>
                        </table>
                    </div>
                
                </div>
                <div id="p2p_bp_style" class="p2p_bp_tab" style="display: none;">
                    <?php $color = $p2p_bp['color']; ?>
                    <table class="p2p_bp_table">      
                        <tr valign="top">
                            <th scope="row"><?php _e('Use Custom Colors', P2P_TRANSLATE); ?>:</th>
                            <td colspan="3">
                                <div>
                                    <?php $colorActive = $control_util->radioCheck($color['active'],'color'); ?>
                                    <?php echo $control_form->createRadioYN('p2p_bp[color][active]',$color['active'],'color_active_btn'); ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div id="color_active" style="display: <?php echo (isset($colorActive['display_color']))?$colorActive['display_color']:''; ?>">
                        <h4 class="p2p_bp_subtitle"><?php _e('Label', P2P_TRANSLATE); ?></h4>
                        <table class="p2p_bp_table">      
                            <tr valign="top">
                                <th scope="row"><?php _e('Font', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->createInput("p2p_bp[color][label_color]", $color['label_color'], "colorfield"); ?></td>
                            </tr>
                            <tr valign="top">                
                                <th scope="row"><?php _e('Background', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->createInput("p2p_bp[color][label_background]", $color['label_background'], "colorfield"); ?></td>
                            </tr>                
                            <tr valign="top">
                                <th scope="row"><?php _e('Border', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3">
                                    <div>
                                        <?php $labelBorder = $control_util->radioCheck($color['label_border'],'label'); ?>
                                        <?php echo $control_form->createRadioYN('p2p_bp[color][label_border]',$color['label_border'],'color_label'); ?>
                                    </div>
                                    <div id="label_border_color" style="display: <?php echo (isset($labelBorder['display_label']))?$labelBorder['display_label']:''; ?>; margin-top:10px;"><?php echo $control_form->createInput("p2p_bp[color][label_border_color]", $color['label_border_color'], "colorfield"); ?></div>
                                </td>
                            </tr>
                        </table>
                        <h4 class="p2p_bp_subtitle"><?php _e('Input', P2P_TRANSLATE); ?></h4>
                        <table class="p2p_bp_table">      
                            <tr valign="top">
                                <th scope="row"><?php _e('Font', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->createInput("p2p_bp[color][input_color]", $color['input_color'], "colorfield"); ?></td>
                            </tr>
                            <tr valign="top">                
                                <th scope="row"><?php _e('Background', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->createInput("p2p_bp[color][input_background]", $color['input_background'],"colorfield"); ?></td>
                            </tr>                
                            <tr valign="top">
                                <th scope="row"><?php _e('Border', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3">
                                    <div>
                                        <?php $inputBorder = $control_util->radioCheck($color['input_border'],'input'); ?>
                                        <?php echo $control_form->createRadioYN('p2p_bp[color][input_border]',$color['input_border'],'color_input'); ?>
                                    </div>
        
                                    <div id="input_border_color" style="display: <?php echo (isset($inputBorder['display_input']))?$inputBorder['display_input']:''; ?>; margin-top:10px;"><?php echo $control_form->createInput("p2p_bp[color][input_border_color]", $color['input_border_color'], "colorfield"); ?></div>
                                </td>
                            </tr>
                        </table>
                        <h4 class="p2p_bp_subtitle"><?php _e('Button', P2P_TRANSLATE); ?></h4>
                        <table class="p2p_bp_table">      
                            <tr valign="top">
                                <th scope="row"><?php _e('Font', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->createInput("p2p_bp[color][button_color]", $color['button_color'], "colorfield"); ?></td>
                            </tr>
                            <tr valign="top">                
                                <th scope="row"><?php _e('Background', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3"><?php echo $control_form->createInput("p2p_bp[color][button_background]", $color['button_background'], "colorfield"); ?></td>
                            </tr>                
                            <tr valign="top">
                                <th scope="row"><?php _e('Border', P2P_TRANSLATE); ?>:</th>
                                <td colspan="3">
                                    <div>
                                        <?php $buttonBorder = $control_util->radioCheck($color['button_border'],'button'); ?>
                                        <?php echo $control_form->createRadioYN('p2p_bp[color][button_border]',$color['button_border'],'color_button'); ?>
                                    </div>
        
                                    <div id="button_border_color" style="display: <?php echo (isset($buttonBorder['display_button']))?$buttonBorder['display_button']:''; ?>;  margin-top:10px;"><?php echo $control_form->createInput("p2p_bp[color][button_border_color]", $color['button_border_color'], "colorfield"); ?></div>
                                </td>
                            </tr>
                        </table>                
                    </div>
                    <div>
                        <div>
                            <div><?php echo $control_form->createTextArea('p2p_bp[color][custom_css]', $color['custom_css'], 10, 'w100p'); ?></div>
                            <div><?php _e('Paste your CSS code, do not include any tags or HTML in this field. Any custom CSS entered here will override your custom CSS. In some cases, the <i>!important</i> tag may be needed.', P2P_TRANSLATE); ?></div>
                        </div>
                    </div>
                </div>
                <div id="p2p_bp_calendar" class="p2p_bp_tab" style="display: none;">
                    <?php $calendar = $p2p_bp['calendar']; ?>
                    <div>
                        <table class="p2p_bp_table">
                            <tr valign="top">
                                <th scope="row"><?php _e('Use Google Calendar',P2P_TRANSLATE); ?>:</th>
                                <td colspan="3">
                                    <?php $calendarActive = $control_util->radioCheck($calendar['enabled'],'calendar'); ?>
                                    <?php echo $control_form->createRadioYN('p2p_bp[calendar][enabled]', $calendar['enabled'], 'calendar_switch'); ?>
                                </td>
                            </tr>
                        </table>
                        
                        <div id="calendar_active" style="display: <?php echo $calendarActive['display_calendar']; ?>">
                            <h4 class="p2p_bp_subtitle"><?php _e('Google Calendar', P2P_TRANSLATE); ?></h4>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php _e('Key file name',P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createInput("p2p_bp[calendar][keyfilename]", $calendar['keyfilename'], "w100p"); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Service account email address',P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createInput("p2p_bp[calendar][serviceemail]", $calendar['serviceemail'], "w100p"); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Calendar Id',P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createInput("p2p_bp[calendar][id]", $calendar['id'], "w100p"); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Time Zone',P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo get_option('timezone_string').' ('.__('GMT Offset', P2P_TRANSLATE).': '.get_option('gmt_offset').') /'.__('This plugin use the WordPress Time-zone', P2P_TRANSLATE).' (<a href="options-general.php" target="_blank">'.__('Click here do change', P2P_TRANSLATE).'</a>) '; ?></td>
                                </tr>
                                <?php
                                if ($calendar['keyfilename'] &&
                                    $calendar['serviceemail'] &&
                                    $calendar['id']
                                ) {
                                ?>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Test Data',P2P_TRANSLATE); ?>:</th>
                                    <td colspan="3"><div id="calendarTest" class="p2p_bp_save p2p_bp_save_intern"><?php _e('Test Settings',P2P_TRANSLATE); ?></div></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </table>
                            <div id="divCalendarTest" style="display:none; min-height: 52px;">
                                <div id="calendarStatus"><?php _e('Please wait',P2P_TRANSLATE); ?> <strong><img src="<?php echo P2P_DIR_IMAGES; ?>ajax-loader.gif" /> <?php _e('Loading...', P2P_TRANSLATE); ?></strong></div>
                            </div>                     
                            <h4 class="p2p_bp_subtitle"><?php _e('Calendar Info',P2P_TRANSLATE); ?></h4>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row" class="w20p calendarTable"><?php _e('Title',P2P_TRANSLATE); ?></th>
                                    <td colspan="2" class="w60p calendarTable"><?php echo $control_form->createInput("p2p_bp[calendar][title]", $calendar['title'], "w100p p2p_bp_calendar_title"); ?></td>
                                    <td scope="row" class="w20p calendarTable"><?php echo $control_form->valuesCalendar('p2p_bp_calendar_title'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" class="w20p calendarTable"><?php _e('Description',P2P_TRANSLATE); ?>: <?php _e('Client Info',P2P_TRANSLATE); ?></th>
                                    <td colspan="2" class="w60p calendarTable"><?php echo $control_form->createTextArea('p2p_bp[calendar][description_client]', $calendar['description_client'], 5, 'w100p p2p_bp_calendar_description_client'); ?></td>
                                    <td scope="row" class="w20p calendarTable"><?php echo $control_form->valuesCalendar('p2p_bp_calendar_description_client'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row" class="w20p calendarTable"><?php _e('Description',P2P_TRANSLATE); ?>: <?php _e('Location Info',P2P_TRANSLATE); ?></th>
                                    <td colspan="2" class="w60p calendarTable"><?php echo $control_form->createTextArea('p2p_bp[calendar][description_location]', $calendar['description_location'], 5, 'w100p p2p_bp_calendar_description_location'); ?></td>
                                    <td scope="row" class="w20p calendarTable"><?php echo $control_form->valuesCalendar('p2p_bp_calendar_description_location'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>  
                
            
                <div id="p2p_bp_payment" class="p2p_bp_tab" style="display: none;">
                    <?php $payment = $p2p_bp['payment']; ?>
                    <div>
                        <?php
                            //Check with payment is activated
                            $paymentType = $control_form->paymentType($payment['type']);
                        ?>
                        <div class="p2p_bp_radio">
                            <input type="radio" name="p2p_bp[payment][type]" id="payment_no" value='no' <?php echo $paymentType['checked_no']; ?>> 
                                <label for="payment_no"><?php _e('No Payment', P2P_TRANSLATE); ?></label>
                            
                            <input type="radio" name="p2p_bp[payment][type]"  id="paypal" value='paypal' <?php echo $paymentType['checked_paypal']; ?>> 
                                <label for="paypal"><?php _e('PayPal', P2P_TRANSLATE); ?></label>
                            
                            <input type="radio" name="p2p_bp[payment][type]" id="braintree" value='braintree' <?php echo $paymentType['checked_braintree']; ?>> 
                                <label for="braintree"><?php _e('BrainTree', P2P_TRANSLATE); ?></label>
                        </div>
                        <div id="divPaymentNo" style="display: <?php echo $paymentType['display_no']; ?>;">
                            <h3 class="p2p_bp_title"><?php _e('No Payment Gateway', P2P_TRANSLATE); ?></h3>
                            <table class="p2p_bp_table">
                                <tr valign="top">
                                    <td><?php _e('Receive Credit Card Info?', P2P_TRANSLATE); ?> (<?php _e('Sandbox', P2P_TRANSLATE); ?>) </td>
                                    <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[payment][nopayment_creditcard]', $payment['nopayment_creditcard'], 'nopayment_creditcard'); ?> </td>
                                </tr>
                                <tr valign="top">
                                    <td colspan="4">
                                        <div class="p2p_bp_alert p2p_bp_error"><?php _e('Use at your own risk.<BR><BR>Use this option in production mode is highly discouraged, as this represents a major issue of security for your clients.<BR><BR>The card data won\'t be validated by the plugin!<BR><BR>Ps. The card data will not be saved in the database. They will be sent to the registered email in the Mail tab.<BR>', P2P_TRANSLATE); ?></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="divPayPal" style="display: <?php echo $paymentType['display_paypal']; ?>;">
                            <h3 class="p2p_bp_title"><?php _e('PayPal', P2P_TRANSLATE); ?></h3>
                            <table class="p2p_bp_table">
                            <?php
                                ($payment['paypal_enviroment']=='live')?$checked_sandbox='checked':$checked_sandbox='';
                                ($payment['paypal_enviroment']=='production')?$checked_production='checked':$checked_production='';
                            ?>        
                                <tr valign="top">
                                    <th scope="row"><?php _e('Enviroment', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[payment][paypal_enviroment]', $payment['paypal_enviroment'], 'paypal_enviroment', array('sandbox', 'live'), array('Sandbox','Live')); ?></td>
                                </tr>		
                                
                                <tr valign="top">
                                    <th scope="row"><?php _e('Client ID', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[payment][paypal_client_ID]', $payment['paypal_client_ID'], 'w100p'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Secret', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createTextArea('p2p_bp[payment][paypal_secret]', $payment['paypal_secret'], 5, 'w100p'); ?></td>
                                </tr>
                            </table>    
                        </div>
                        <div id="divBrainTree" style="display: <?php echo $paymentType['display_braintree']; ?>;">
                            <h3 class="p2p_bp_title"><?php _e('BrainTree', P2P_TRANSLATE); ?></h3>
                            <table class="p2p_bp_table">
                        <?php
                                ($payment['braintree_enviroment']=='sandbox')?$checked_sandbox='checked':$checked_sandbox='';
                                ($payment['braintree_enviroment']=='production')?$checked_production='checked':$checked_production='';
                        ?>        
                                <tr valign="top">
                                    <th scope="row"><?php _e('Enviroment', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[payment][braintree_enviroment]', $payment['braintree_enviroment'], 'braintree_enviroment', array('sandbox', 'production'), array('Sandbox','Production')); ?></td>
                                </tr>		
                                <tr valign="top">
                                    <th scope="row"><?php _e('Merchant ID', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[payment][braintree_merchantId]', $payment['braintree_merchantId'], 'w100p'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Public Key', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[payment][braintree_publicKey]', $payment['braintree_publicKey'], 'w100p'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Private Key', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[payment][braintree_privateKey]', $payment['braintree_privateKey'], 'w100p'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Configuration Code', P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $control_form->createTextArea('p2p_bp[payment][braintree_config_code]', $payment['braintree_config_code'], 5, 'w100p'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>        
                <div id="p2p_bp_email" class="p2p_bp_tab" style="display: none;">
                    <?php $email = $p2p_bp['email']; ?>
                    <div>
                        <table class="p2p_bp_table">
                            <tr>
                                <td class="w20p"><?php _e('Name', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createInput('p2p_bp[email][name]', $email['name'], 'w100p'); ?></td>
                            </tr>
                            <tr>
                                <td class="w20p"><?php _e('E-mail', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createInput('p2p_bp[email][address]', $email['address'],'w100p'); ?></td>
                            </tr>
                            <tr>
                                <td class="w20p"><?php _e('Password', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createInput('p2p_bp[email][pass]', $email['pass'], 'w100p', 'password'); ?></td>
                            </tr>
                            <tr>
                                <td class="w20p"><?php _e('SMTP Secure', P2P_TRANSLATE); ?></td>
                                <td class="w80p">
                                    <div class="p2p_bp_radio">
                                        <input type="radio" name="p2p_bp[email][smtpsecure]" id="no" value='no' <?php echo ($email['smtpsecure'] == 'no')?'checked':''; ?>> 
                                            <label id="no" class="<?php echo ($email['smtpsecure'] == 'no')?'active':''; ?>"><?php _e('No Secure', P2P_TRANSLATE); ?></label>
                                        <input type="radio" name="p2p_bp[email][smtpsecure]"  id="ssl" value='ssl' <?php echo ($email['smtpsecure'] == 'ssl')?'checked':''; ?>> 
                                            <label id="paypal" class="<?php echo ($email['smtpsecure'] == 'ssl')?'active':''; ?>"><?php _e('SSL', P2P_TRANSLATE); ?></label>
                                        <input type="radio" name="p2p_bp[email][smtpsecure]" id="tls" value='tls' <?php echo ($email['smtpsecure'] == 'tls')?'checked':''; ?>> 
                                            <label id="braintree" class="<?php echo ($email['smtpsecure'] == 'tls')?'active':''; ?>"><?php _e('TLS', P2P_TRANSLATE); ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="w20p"><?php _e('Host', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createInput('p2p_bp[email][host]', $email['host'], 'w100p'); ?></td>
                            </tr>
                            <tr>
                                <td class="w20p"><?php _e('Port', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createInput('p2p_bp[email][port]', $email['port'], 'w100p'); ?></td>
                            </tr>
                            <tr valign="top">
                                <td class="w20p"><?php _e('Send email to WP admin?', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createRadioYN('p2p_bp[email][admin]', $email['admin'], 'admin'); ?> </td>
                            </tr>
                            <tr valign="top">
                                <td class="w20p"><?php _e('Debug errors?', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createRadioYN('p2p_bp[email][debug]', $email['debug'], 'debug'); ?> </td>
                            </tr>
                            <tr valign="top">
                                <td class="w20p"><?php _e('User cUrl to get template?', P2P_TRANSLATE); ?></td>
                                <td class="w80p"><?php echo $control_form->createRadioYN('p2p_bp[email][curl]', $email['curl'], 'curl'); ?> <small><?php _e('Try a differente setting if you are getting the message "E-mail body is empty".', P2P_TRANSLATE); ?></small></td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <h3 class="p2p_bp_title"><?php _e('E-mail Info', P2P_TRANSLATE); ?></h3>
                        <div>            
                            <table class="p2p_bp_table">
                                <tr valign="top">
                                    <td scope="row"><?php _e('Signature', P2P_TRANSLATE); ?></td>
                                    <td colspan="6"><?php echo $control_form->createTextArea('p2p_bp[email][signature]', $email['signature'], 5, 'w100p'); ?></td>
                                    <td><small><?php _e('It\'s allowed to insert HTML tags.', P2P_TRANSLATE); ?><BR><?php _e('To open a new line, insert &lt;BR&gt; at end of the line.', P2P_TRANSLATE); ?> </small></td>
                                </tr>
                                <tr valign="top">
                                    <td scope="row" colspan="8"><strong><?php _e('E-mail Title Colors', P2P_TRANSLATE); ?>:</strong></td>
                                </tr>
                                <tr valign="top">
                                    <td scope="row"><?php _e('Background', P2P_TRANSLATE); ?></td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][color_bg]', $email['color_bg'],'colorfield'); ?></td>
                                    <td scope="row"><?php _e('Font', P2P_TRANSLATE); ?></td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][color_txt]', $email['color_txt'],'colorfield'); ?></td>
                                </tr>
                            </table> 		
                        </div>
                    </div>
                    <div>
                        <h3 class="p2p_bp_title"><?php _e('E-mail Receiver', P2P_TRANSLATE); ?></h3>
                        <div>            
                            <table class="p2p_bp_table">
                                <tr valign="top">
                                    <td scope="row"><?php _e('E-mail', P2P_TRANSLATE); ?> 1</td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][receive_1]', $email['receive_1'], 'w100p'); ?></td>
                                    <td scope="row"><?php _e('Name', P2P_TRANSLATE); ?> 1</td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][receive_name_1]', $email['receive_name_1'], 'w100p'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <td scope="row"><?php _e('E-mail', P2P_TRANSLATE); ?> 2</td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][receive_2]', $email['receive_2'], 'w100p'); ?></td>
                                    <td scope="row"><?php _e('Name', P2P_TRANSLATE); ?> 2</td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][receive_name_2]', $email['receive_name_2'], 'w100p'); ?></td>
                                </tr>
                                <tr valign="top">
                                    <td scope="row"><?php _e('E-mail', P2P_TRANSLATE); ?> 3</td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][receive_3]', $email['receive_3'], 'w100p'); ?></td>
                                    <td scope="row"><?php _e('Name', P2P_TRANSLATE); ?> 3</td>
                                    <td colspan="3"><?php echo $control_form->createInput('p2p_bp[email][receive_name_3]', $email['receive_name_3'], 'w100p'); ?></td>
                                </tr>
                            </table> 		
                        </div>
                    </div>
                </div>
                <div id="p2p_bp_api" class="p2p_bp_tab" style="display: none;">
                    <div>
                        <div id="divBigpromoterTest" style="display:block; min-height: 52px;">
                            <div id="apiStatus" style="display:none;"><div class="p2p_bp_alert p2p_bp_info">API <?php _e('Status', P2P_TRANSLATE); ?>: <strong><img src="<?php echo P2P_DIR_IMAGES; ?>ajax-loader.gif" /> <?php _e('Loading...', P2P_TRANSLATE); ?></strong></div></div>
                            <div id="checkApi"><div class="p2p_bp_alert p2p_bp_info" style="cursor: pointer;"><?php _e('Click to Check API status', P2P_TRANSLATE); ?></div></div>
                        </div>        
                        <table class="p2p_bp_table">
                            <tr>
                                <th><?php _e('Token', P2P_TRANSLATE); ?></th>
                                <td><?php echo $control_form->createInput('p2p_bp_api_token', get_option('p2p_bp_api_token'), 'w100p'); ?></td>
                            </tr>
                            <tr>
                                <th><?php _e('Site', P2P_TRANSLATE); ?></th>
                                <td><?php echo $control_form->createInput('p2p_bp_api_site', get_option('p2p_bp_api_site'),'w100p'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="p2p_bp_advanced" class="p2p_bp_tab" style="display: none;">
                    <?php $advanced = $p2p_bp['advanced']; ?>
                    <div>
                        <table class="p2p_bp_table">
                            <tr>
                                <td><?php _e('Delete table when deactivate plugin?', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[advanced][delete_table]', $advanced['delete_table'], 'delete_table'); ?> </td>
                            </tr>
                            <tr>
                                <td><?php _e('Delete settings when deactivate plugin?', P2P_TRANSLATE); ?></td>
                                <td colspan="3"><?php echo $control_form->createRadioYN('p2p_bp[advanced][delete_settings]', $advanced['delete_settings'],'delete_settings'); ?></td>
                            </tr>                        
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="p2p_bp_footer" class="left w100p">
            <div class="dash_right">
                <div class="right"><button type="submit" name="submit" id="submit" class="p2p_bp_save right"><?php _e('Save Changes', P2P_TRANSLATE); ?></button></div>
            </div>
        </div>
        <?php echo $control_form->createInput('p2p_bp_last_tab', get_option('p2p_bp_last_tab'), '', 'hidden'); ?>
    </form>
</div>
<script>
    jQuery(function () {
        //After Page Loaded
        jQuery('#p2p_bp_loading').fadeOut(500, function () {
            jQuery('#<?php echo get_option('p2p_bp_last_tab'); ?>').fadeIn(500, function () {
                settings();
            });
        });
    });
</script>