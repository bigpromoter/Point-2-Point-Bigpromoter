<?php
class P2P_Reservation {
    private $option;
    private $model_reservation;
    private $control_payment;
    private $control_util;
	function __construct() {
        $this->option = get_option('p2p_bp');
        
        include_once (P2P_DIR_MODEL . 'reservation.php');
        $this->model_reservation = new P2P_Model_Reservation();
        
        include_once (P2P_DIR_CONTROL . 'payment.php');
        $this->control_payment = new P2P_Payment();
        
        include_once (P2P_DIR_CONTROL . 'util.php');
        $this->control_util = new P2P_Util();
	}
    
    function validationEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return 1;
        return 0;
    }
    
    function validationInt($num) {
        if (!filter_var($num, FILTER_VALIDATE_INT)) return 1;
        return 0;
    }
    
    function validationStr($str, $char = 0) {
        if (strlen($str) < $char) return 1;
        return 0;
    }

    function validationDate($date, $empty = 0) {
        if (strlen($date) == 0 && $empty) return 1;
        else {
            if (strlen($date) == 0 && $empty == 0)
                if ((isset($_POST['r']) && $_POST['r'] == 0))
                    return 0;
            else {
                $d = explode('/',$date);
                if (count($d) != 3) return 1;
                if (!checkdate($d[0], $d[1], $d[2])) return 1;
            }
        }
        return 0;
    }

    function validationService($value) {
        if ($value != '0') return 0;
        return 1;
    }
    
    function checkErros($info, $fields) {
        $error = 0;
        $error_msg = Array();
        foreach ($fields as $f) {
            if ($f[1] == 'str') {
                $return = $this->validationStr($info[$f[0]],$f[2]);
            } else if ($f[1] == 'int') {
                $return = $this->validationInt(intval($info[$f[0]]));
            } else if ($f[1] == 'email') {
                $return = $this->validationEmail($info[$f[0]]);
            } else if ($f[1] == 'date') {
                $return = $this->validationDate($info[$f[0]],$f[2]);
            } else if ($f[1] == 'service') {
                $return = $this->validationService($info[$f[0]]);
            }
            $error += $return;
            $error_msg[$f[0]] = $return; 
        }
        $check[0] = $error;
        $check[1] = $error_msg;
        return $check;
    }
    
    function doReservation($info, $chooseCar, $price) {
        if ($this->option['calendar']['enabled']) require_once (P2P_DIR_CALENDAR.'calendar.php');
        //$model = new p2p_bp_ModelUser();
        //Check missfilled fields
        $fields =  array(
                        array('first_name','str',2),
                        array('last_name','str',2),
                        array('email','email'),
                        array('npassenger','int'),
                        array('nluggage','int'),
                        array('p_date','date',1),
                        array('r_p_date','date',0),
                        array('servicetype','service')
                    );
        $check = $this->checkErros($info, $fields);
        $error = $check[0];
        $er = $check[1];
        
        $final_price = (isset($info['r']) && ($info['r'] == 1))?$price*2:$price;
        $er['gratuity'] = false;
        $info['trip'] = $final_price;
        //$info['gratuity'] = 0;
        if (isset($info['gratuity']) && (get_option('insert_gratuity'))) {
            if (is_numeric($info['gratuity'])) {
                if ($info['gratuity'] < 0) {$error++; $er['gratuity'] = true;}
                $final_price = round(($final_price * (1 + ((float)$info['gratuity']/100))),2);
            } else {
                if (isset($info['gratuityOther_input']) && is_numeric($info['gratuityOther_input'])) {
                    if ($info['gratuityOther_input'] < 0) {$error++; $er['gratuity'] = true;}
                    $final_price = $final_price + (float)$info['gratuityOther_input'];
                }
            }
        }
        $info['gratuity'] = $final_price - $info['trip'];
        
        
        //Adjust AMPM time
        if (isset($info['p_time_ampm'])) {
            $ampm = $info['p_time_ampm'];
            if ($info['p_time_h'] == 12) $info['p_time_h'] = (strtoupper($ampm) == 'AM')?0:12;
            else $info['p_time_h'] = (strtoupper($ampm) == 'AM')?$info['p_time_h']:$info['p_time_h']+12;
            
            if ($info['r']) {
                $r_ampm = $info['r_p_time_ampm'];
                if ($info['r_p_time_h'] == 12) $info['r_p_time_h'] = (strtoupper($r_ampm) == 'AM')?0:12;
                else $info['r_p_time_h'] = (strtoupper($r_ampm) == 'AM')?$info['r_p_time_h']:$info['r_p_time_h']+12;    
            }
        }
        
        //Create Extra Requirement
        
        $info['extra'] = '';
        if (isset($info['car_seat'])) {
            $info['extra'] = 'Car Seat ('.$this->option['basic']['select_currency'].$info['car_seat'];
            $final_price = $final_price + (float)$info['car_seat'];
            $info['p_instructions'] .= ' ['.__('Extra',P2P_TRANSLATE).': '.__('Car Seat',P2P_TRANSLATE).' ('.$this->option['basic']['select_currency'].$info['car_seat'].')]';
        }
        
        $info['r_extra'] = '';
        if ($info['r'] && isset($info['r_car_seat'])) {
            $info['r_extra'] = 'Car Seat ('.get_option('select_currency').$info['r_car_seat'].') ';
            if (isset($info['car_seat'])) $info['extra'] .= ' (+'.$this->option['basic']['select_currency'].$info['r_car_seat'].' '.__('for round trip request',P2P_TRANSLATE).')';
            $final_price = $final_price + (float)$info['r_car_seat'];
            $info['r_p_instructions'] .= ' ['.__('Extra',P2P_TRANSLATE).': '.__('Car Seat',P2P_TRANSLATE).' ('.$this->option['basic']['select_currency'].$info['r_car_seat'].')]';
        }
        
        if (isset($info['car_seat'])) $info['extra'] .= ') ';
    
        $info['final_price'] = $final_price;
        
        //End Check
        if (!($error)) { //If there is no Error
            // Check if reservation was already made
            if ($this->model_reservation->checkIfAlreadyMade($info)) {
                echo '<div class="alert p2p_bp_alert p2p_bp_warning">'.__('We already have your reservation', P2P_TRANSLATE).'!</div>';
            } else {
                $payment_info = '';
                $info['send_cardinfo'] = ($this->option['payment']['type'] == 'no' && $this->option['payment']['nopayment_creditcard'])?true:false;
                if ($this->option['payment']['type'] == 'braintree') {
                    $payment = $this->control_payment->paymentBrainTree($final_price,$info);
                    echo $payment[1]; //Print Payment return
                    $payment_info['paid'] = $payment[2];
                    $payment_info['id'] = $payment[3];
                    $payment_info['company'] = $payment[4];
                } else if ($this->option['payment']['type'] == 'paypal') {
                    $payment = $this->control_payment->paymentPayPal($final_price,$info);
                    echo $payment[1]; //Print Payment return
                    $payment_info['paid'] = $payment[2];
                    $payment_info['id'] = $payment[3];
                    $payment_info['company'] = $payment[4];
                } else {
                    $payment[0] = true;
                    $payment_info['paid'] = $final_price;
                    $payment_info['id'] = __('No Payment',P2P_TRANSLATE);
                    $payment_info['company'] = __('No Payment',P2P_TRANSLATE);
                    if ($info['send_cardinfo']) {
                        $info['card_info'] = "<BR>".__("Type", P2P_TRANSLATE).": ".$info['cardtype']."<BR>".__('Credit Card', P2P_TRANSLATE).": ".$info['card_num']."<BR>".__('CVV', P2P_TRANSLATE).": ".$info['card_cvv']."<BR>".__('Expire', P2P_TRANSLATE).": ".$info['card_month']."/".$info['card_year']."<BR>".__('Zip Code', P2P_TRANSLATE).": ".$info['zip_code'];
                    }
                }
                
                if ($payment[0]) { //If Payment is OK
                    if ($this->option['calendar']['enabled']) {
                        $insertCalendar = new GoogleCalendar();
                        if ($info['r']) {
                            $resultCalendarRoundTrip = $insertCalendar->insert_event($info, $chooseCar, $payment_info, 0, 1, 1);
                            $resultCalendarRoundTrip = $insertCalendar->insert_event($info, $chooseCar, $payment_info, 0, 1, 2);
                        } else {
                            $resultCalendar = $insertCalendar->insert_event($info, $chooseCar, $payment_info);
                        }
                    }
                    $insert = $this->model_reservation->insertReservation($info, $payment_info);
                    if ($insert) {
?>
                        <div class="p2p_bp_alert p2p_bp_success"><?php _e('We got your reservation', P2P_TRANSLATE); ?>!</div>
<?php
                        //Send Mail
                        $sendMail = $this->control_util->sendMail($info);
                        $sendMail_Client = $this->control_util->sendMail($info,'client');
                        if($sendMail['sent'] == false) { //Check if e-mail was sent
                            if (!empty($sendMail['error']) && ($this->option['email']['debug'])) {
                                echo "<div class='alert p2p_bp_alert p2p_bp_error'>"._('Mailer Error', P2P_TRANSLATE).": " . $sendMail['error']."</div>";
                            }
                        }
                    } 
                }
            }
        } else { //If there is Error, show them
            if ($er['first_name']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('You must fill your',P2P_TRANSLATE).' <strong>'.__('First Name',P2P_TRANSLATE).'</strong>!</div>';
            if ($er['last_name']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('You must fill your',P2P_TRANSLATE).' <strong>'.__('Last Name',P2P_TRANSLATE).'</strong>!</div>';
            if ($er['email']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('You must fill a valid',P2P_TRANSLATE).' <strong>'.__('E-mail',P2P_TRANSLATE).'</strong>!</div>';
            if ($er['npassenger']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('The field',P2P_TRANSLATE).' <strong>'.__('Passengers',P2P_TRANSLATE).'</strong> '.__('must be numeric',P2P_TRANSLATE).'!</div>';
            if ($er['nluggage']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('The field',P2P_TRANSLATE).' <strong>'.__('Luggage',P2P_TRANSLATE).'</strong> '.__('must be numeric',P2P_TRANSLATE).'!</div>';
            if ($er['servicetype']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('You must fill a valid',P2P_TRANSLATE).' <strong>'.__('Service',P2P_TRANSLATE).'</strong>!</div>';
            if ($er['p_date']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('You must fill a valid',P2P_TRANSLATE).' <strong>'. __('Pick-up Date',P2P_TRANSLATE).'</strong>!</div>';
            if ($er['r_p_date']) echo '<div class="alert p2p_bp_alert p2p_bp_error">'.__('You must fill a valid',P2P_TRANSLATE).' <strong>'.__('Round-Trip',P2P_TRANSLATE).' '. __('Pick-up Date',P2P_TRANSLATE).'</strong>!</div>';
            if ($er['gratuity']) echo '<div class="alert p2p_bp_alert p2p_bp_error"><strong>'.__('Gratuity',P2P_TRANSLATE).'</strong> '.__('value cannot be less than 0',P2P_TRANSLATE).'!</div>';
        }
        
        return $er;
    }    
}
?>