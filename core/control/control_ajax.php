<?php
class P2P_Ajax {

    private $option;
	function __construct() {    
        add_action('wp_ajax_p2p_bp_calendar',array($this, 'p2p_bp_calendar_callback'));
        add_action('wp_ajax_p2p_bp_export',array($this, 'p2p_bp_export_callback'));
        add_action('wp_ajax_p2p_bp_import',array($this, 'p2p_bp_import_callback'));
        add_action('wp_ajax_p2p_bp_delete_reservation',array($this, 'p2p_bp_delete_reservation_callback'));
        add_action('wp_ajax_p2p_bp_get_reservation',array($this, 'p2p_bp_p2p_bp_get_reservation_callback'));
        add_action('wp_ajax_p2p_bp_provided',array($this, 'p2p_bp_provided_callback'));
        
        $this->option = get_option('p2p_bp');
	}
    
    function p2p_bp_provided_callback() {
        if (!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'p2p_bp_nonce')) wp_die(__('Permissions check failed!',P2P_TRANSLATE));
        
        include_once P2P_DIR_MODEL."reservation.php";
        $model_reservation = new P2P_Model_Reservation();
        $is_provided = $model_reservation->isProvided($_POST['id'], $_POST['round']);
        $change_provided = $model_reservation->changeProvided($_POST['id'], $_POST['round'], ($is_provided)?0:1);
        $round = ($_POST['round'])?'_r':'';
        $child = ($_POST['round'])?'right':'left';
        if ($is_provided == 0) {
    ?>
        <script>
            jQuery('.p2p_bp_ball<?php echo $round.$_POST['id']; ?>').removeClass('p2p_bp_ball_red').addClass('p2p_bp_ball_green');
            jQuery('#table<?php echo $_POST['id']; ?> > .reservationProvided > .p2p_bp_ball.<?php echo $child; ?>').removeClass('p2p_bp_ball_red').addClass('p2p_bp_ball_green');
        </script>
    <?php
        } else {
    ?>
        <script>
            jQuery('.p2p_bp_ball<?php echo $round.$_POST['id']; ?>').addClass('p2p_bp_ball_red').removeClass('p2p_bp_ball_green');
            jQuery('#table<?php echo $_POST['id']; ?> > .reservationProvided > .p2p_bp_ball.<?php echo $child; ?>').addClass('p2p_bp_ball_red').removeClass('p2p_bp_ball_green');
        </script>
    <?php
        }
        
        wp_die();    
    }
    
    function p2p_bp_p2p_bp_get_reservation_callback() {
        if (!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'p2p_bp_nonce')) wp_die(__('Permissions check failed!',P2P_TRANSLATE));
        include_once P2P_DIR_CONTROL."util.php";
        $control_util = new P2P_Util();
        include_once P2P_DIR_MODEL."reservation.php";
        $model_reservation = new P2P_Model_Reservation();
        $reserve = $model_reservation->getReserve($_POST['id']);
        $reserve = $reserve[0];
        
?>
        <table class="reserveTab">
            <tbody>
                <tr class="reserveTabTitle">
                    <td colspan="4"><?php _e('Client Information',P2P_TRANSLATE); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Name',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_first_name.' '.$reserve->p2p_bp_last_name; ?></td>
                    <td><?php _e('Email',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_email; ?></td>                
                </tr>
                <tr>
                    <td><?php _e('Phone',P2P_TRANSLATE); ?></td>
                    <td><?php echo $reserve->p2p_bp_phone; ?></td>
                    <td><?php _e('Ip',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_ip; ?></td>                
                </tr>
                <tr class="reserveTabTitle">
                    <td colspan="4"><?php _e('Service Information',P2P_TRANSLATE); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Vehicle Type',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_vehicletype; ?></td>
                    <td><?php _e('Passenger',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_npassenger; ?></td>                
                </tr>
                <tr>
                    <td><?php _e('Service',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_servicetype; ?></td>
                    <td><?php _e('Luggage',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_nluggage; ?></td>                
                </tr>
                <tr class="reserveTabTitle">
                    <td colspan="4"><?php _e('Payment Information',P2P_TRANSLATE); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Transaction ID',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_payment_id; ?></td>
                    <td><?php _e('Company',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_payment_company; ?></td>                
                </tr>
                <tr>
                    <?php
                        $gratuity = ((float)$reserve->p2p_bp_payment_gratuity > 0)?$reserve->p2p_bp_payment_gratuity:false;
                        $extra = ((float)$reserve->p2p_bp_payment_total - (float)$reserve->p2p_bp_payment_trip - (float)$reserve->p2p_bp_payment_gratuity);
                        $extra = ((float)$extra > 0)?$extra:false;
                        
                        $value = '('.__('Trip', P2P_TRANSLATE).': '.$reserve->p2p_bp_payment_trip;
                        $value .= ($extra !== false)?' + '.__('Extra', P2P_TRANSLATE).': '.$extra:'';
                        $value .= ($gratuity !== false)?' + '.__('Gratuity', P2P_TRANSLATE).': '.$gratuity:'';
                        $value .= ')';
                    ?>
                    <td><?php _e('Value',P2P_TRANSLATE); ?>:</td>
                    <td colaspan="3"><?php echo $this->option['basic']['select_currency'].$reserve->p2p_bp_payment_total; ?> <small><?php echo $value;?></small></td>           
                </tr>
                <tr class="reserveTabTitle">
                    <td colspan="4">
                        <?php if($reserve->p2p_bp_done) { ?>
                            <div id="<?php echo $reserve->p2p_bp_id; ?>" class="p2p_bp_ball<?php echo $reserve->p2p_bp_id; ?> p2p_bp_ball p2p_bp_ball_green p2p_bp_change_provided left"></div>
                        <?php } else { ?>
                            <div id="<?php echo $reserve->p2p_bp_id; ?>" class="p2p_bp_ball<?php echo $reserve->p2p_bp_id; ?> p2p_bp_ball p2p_bp_ball_red p2p_bp_change_provided left"></div>
                        <?php } ?>
                        <?php _e('Trip Information',P2P_TRANSLATE); ?>
                    </td>
                </tr>
                <tr class="reserveTabSubTitle">
                    <td colspan="2"><?php _e('Pick-Up Information',P2P_TRANSLATE); ?></td>
                    <td colspan="2"><?php _e('Drop-Off Information',P2P_TRANSLATE); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Address',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_p_apt.' '.$reserve->p2p_bp_p_address; ?></td>
                    <td><?php _e('Address',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_d_apt.' '.$reserve->p2p_bp_d_address; ?></td>              
                </tr>
                <tr>
                    <td><?php _e('City/State',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_p_city.'/'.$reserve->p2p_bp_p_state; ?></td>
                    <td><?php _e('City/State',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_d_city.'/'.$reserve->p2p_bp_d_state; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Zip Code',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_p_zip; ?></td>
                    <td><?php _e('Zip Code',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_d_zip; ?></td>
                </tr>
                <tr>
                    <td><?php _e('When',P2P_TRANSLATE); ?>:</td>
                    <td colspan="3"><?php echo $control_util->changeDate($reserve->p2p_bp_p_date,true).' '.__('at',P2P_TRANSLATE).' '.date('h:i A', strtotime($reserve->p2p_bp_p_time)); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Special Instructions',P2P_TRANSLATE); ?>:</td>
                    <td colspan="3"><?php echo $reserve->p2p_bp_p_instructions; ?></td>
                </tr>
                
                <?php if ($reserve->p2p_bp_r == 1) { ?>
                <tr class="reserveTabTitle">
                    <td colspan="4">
                        <?php if($reserve->p2p_bp_done_r) { ?>
                            <div id="<?php echo $reserve->p2p_bp_id; ?>" class="p2p_bp_ball_r<?php echo $reserve->p2p_bp_id; ?> p2p_bp_ball p2p_bp_ball_green p2p_bp_change_provided_r left"></div>
                        <?php } else { ?>
                            <div id="<?php echo $reserve->p2p_bp_id; ?>" class="p2p_bp_ball_r<?php echo $reserve->p2p_bp_id; ?> p2p_bp_ball p2p_bp_ball_red p2p_bp_change_provided_r left"></div>
                        <?php } ?>
                        <?php _e('Round-Trip Information',P2P_TRANSLATE); ?>
                    </td>
                </tr>
                <tr class="reserveTabSubTitle">
                    <td colspan="2"><?php _e('Pick-Up Information',P2P_TRANSLATE); ?></td>
                    <td colspan="2"><?php _e('Drop-Off Information',P2P_TRANSLATE); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Address',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_d_apt.' '.$reserve->p2p_bp_d_address; ?></td>              
                    <td><?php _e('Address',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_p_apt.' '.$reserve->p2p_bp_p_address; ?></td>
                </tr>
                <tr>
                    <td><?php _e('City/State',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_d_city.'/'.$reserve->p2p_bp_d_state; ?></td>
                    <td><?php _e('City/State',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_p_city.'/'.$reserve->p2p_bp_p_state; ?></td>
                </tr>
                <tr>
                    <td><?php _e('Zip Code',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_d_zip; ?></td>
                    <td><?php _e('Zip Code',P2P_TRANSLATE); ?>:</td>
                    <td><?php echo $reserve->p2p_bp_p_zip; ?></td>
                </tr>
                <tr>
                    <td><?php _e('When',P2P_TRANSLATE); ?>:</td>
                    <td colspan="3"><?php echo $control_util->changeDate($reserve->p2p_bp_r_p_date, true).' '.__('at',P2P_TRANSLATE).' '.date('h:i A', strtotime($reserve->p2p_bp_r_p_time)); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Special Instructions',P2P_TRANSLATE); ?>:</td>
                    <td colspan="3"><?php echo $reserve->p2p_bp_r_p_instructions; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
<?php
        wp_die();
    }
    
    //Test if Google Calendar API is working properly
    function p2p_bp_calendar_callback() {
        if (!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'p2p_bp_nonce')) wp_die(__('Permissions check failed!',P2P_TRANSLATE));

        //Include Core files from WP
        include_once (P2P_DIR_CALENDAR.'calendar.php');
        
        $insertCalendar = new GoogleCalendar();
        $resultCalendar = $insertCalendar->insert_event('', '', '', 1);
        if ( !current_time('timestamp') ) $tdif = 0;
        else $tdif = current_time('timestamp') - time();

        if ($resultCalendar) {
    ?>
        <div class="p2p_bp_alert p2p_bp_success">
            <?php _e("If you didn't get any error, it's time to check your Google Calendar. You should see a new event rigth now!",P2P_TRANSLATE); ?><BR>
            <?php _e("Test made on",P2P_TRANSLATE); ?> <?php echo date('m/d/Y h:i:s A', time() + $tdif); ?>
        </div>
    <?php
        } else {
    ?>
        <div class="p2p_bp_alert p2p_bp_error">
            <?php _e("Something went wrong! Check the settings above, save and try again!",P2P_TRANSLATE); ?><BR>
            <?php _e("Test made on",P2P_TRANSLATE); ?> <?php echo date('m/d/Y h:i:s A', time() + $tdif); ?>
        </div>
    <?php
        }
        wp_die();
    }
    
    //Create Export Code
    function p2p_bp_export_callback() {
        if (!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'p2p_bp_nonce')) wp_die(__('Permissions check failed!',P2P_TRANSLATE));
        $options = $_POST['options'];
        $fleet = $_POST['fleet'];
        $services = $_POST['services'];
        
        if (!$options && !$fleet && !$services) {
            echo '<div class="p2p_bp_alert p2p_bp_error">'.__('You must select at least on option!',P2P_TRANSLATE).'</div>';
        } else {
            echo '<div class="p2p_bp_alert p2p_bp_success">'.__('Paste the code below on your other P2P Plugin!',P2P_TRANSLATE).'</div>';
            $export = array();
            if ($options) {
                $export['options'] = get_option('p2p_bp');
                $export['fleet_increase_ride'] = get_option('p2p_bp_fleet_increase_ride');
                $export['services_others'] = get_option('p2p_bp_services_others');
            }
            
            if ($fleet) $export['fleet'] = get_option('p2p_bp_fleet');
            if ($services) $export['services'] = get_option('p2p_bp_services');
            
            echo '<textarea style="width: 100%; height:200px;">'.base64_encode(serialize($export)).'</textarea>';
            
        }
        
        
        wp_die();
    }
    
    function p2p_bp_import_callback() {
        if (!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'p2p_bp_nonce')) wp_die(__('Permissions check failed!',P2P_TRANSLATE));
        $code = @unserialize(base64_decode($_POST['code']));
        
        if (is_array($code)) {
            if (isset($code['options'])) {
                echo '<div class="p2p_bp_alert p2p_bp_success">'.__('Settings Imported.',P2P_TRANSLATE).'</div>';
                update_option('p2p_bp', $code['options']);
            }
            if (isset($code['fleet_increase_ride'])) update_option('p2p_bp_fleet_increase_ride', $code['fleet_increase_ride']);
            if (isset($code['services_others'])) update_option('p2p_bp_services_others', $code['services_others']);
            if (isset($code['fleet'])) {
                echo '<div class="p2p_bp_alert p2p_bp_success">'.sprintf(__('Fleet Imported. %s cars imported.',P2P_TRANSLATE),count($code['fleet']['body'])).'</div>';
                update_option('p2p_bp_fleet', $code['fleet']);
            }
            if (isset($code['services'])) {
                echo '<div class="p2p_bp_alert p2p_bp_success">'.sprintf(__('Services Imported. %s services imported.',P2P_TRANSLATE),count($code['services']['name'])).'</div>';
                update_option('p2p_bp_services', $code['services']);
            }
        } else {
            echo '<div class="p2p_bp_alert p2p_bp_error">'.__('This code is corrupted! Try Export your settigns again.',P2P_TRANSLATE).'</div>';
        }
        
        wp_die();
    }
    
    function p2p_bp_delete_reservation_callback() {
        if (!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'p2p_bp_nonce')) wp_die(__('Permissions check failed!',P2P_TRANSLATE));
        include_once P2P_DIR_MODEL."reservation.php";
        $model_reservation = new P2P_Model_Reservation();
        $delete = $model_reservation->deleteReservation($_POST['id']);
        
        if ($delete) {
            echo __('Reservation deleted',P2P_TRANSLATE).'!';
    ?>
            <script>
                var id = '<?php echo $_POST['id']; ?>';
                jQuery('#showAjax' + id).addClass('p2p_bp_success').removeClass('p2p_bp_info');
                jQuery('#showAjax' + id).delay(2000).fadeOut(1000, function() { jQuery(this).remove(); });
                jQuery('#table' + id).fadeOut(2000, function() { jQuery(this).remove(); });
            </script>
    <?php
        } else {        
            echo __('Reservation not deleted',P2P_TRANSLATE).'!';
    ?>
            <script>
                var id = '<?php echo $_POST['id']; ?>';
                jQuery('#showAjax' + id).addClass('p2p_bp_error').removeClass('p2p_bp_info');
                jQuery('#showAjax' + id).delay(3000).fadeOut(500);
            </script>
    <?php   
        }
        wp_die();
    }
}

?>