<?php
/* Itens to be used with PayPal Gateway */
    use PayPal\Api\Amount;
    use PayPal\Api\Details;
    use PayPal\Api\Item;
    use PayPal\Api\ItemList;
    use PayPal\Api\CreditCard;        
    use PayPal\Api\Payer;
    use PayPal\Api\Payment;
    use PayPal\Api\FundingInstrument;
    use PayPal\Api\Transaction;

class p2p_bp_ControlUser {

    const DIST_KM = 1000;
    const DIST_MILE = 1609.344;

    function showMap() {
        //Call View
        $html = file_get_contents(dirname(__FILE__)."/../view/map.php");
        $attr =  $this->mapAttr();
        
        $html = str_replace('%customcolor%', $this->color(), $html); //Insert Custom Color
        $html = str_replace('%width%', $attr['map_width'], $html);
        $html = str_replace('%height%', $attr['map_height'], $html);
        $html = str_replace('%start_map_lat%', $attr['start_map_lat'], $html);
        $html = str_replace('%start_map_lon%', $attr['start_map_lon'], $html);
        $html = str_replace('%start_map_zoom%', $attr['start_map_zoom'], $html);
        $html = str_replace('%google_api%', get_option('google_api'), $html);
        $html = str_replace('%plugin_address%', plugins_url('p2p_bigpromoter/'), $html);
        
        return $html;
    }

	function mapAttr() {
		$attr['map_width'] 	    = get_option('map_width');
		$attr['map_height'] 	= get_option('map_height');
		$attr['start_map_lon'] 	= get_option('start_map_lon');
		$attr['start_map_lat']	= get_option('start_map_lat');
		$attr['start_map_zoom'] = get_option('start_map_zoom');
        return $attr;		
	}
    
 	//Access file
	function fileGetContents($site_url) {
		$ch = curl_init();
		$timeout = 10;
		curl_setopt ($ch, CURLOPT_URL, $site_url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}
    
    // Geocode an address: return array of latitude & longitude
	function gmapDistance( $address_start, $address_end, $system ) {
		//Check which unit is on Plugin
		($system=="kms")?$unit = 'metric':$unit = 'imperial';

		// Make Google Geocoding API URL
		$path_url = "http://maps.googleapis.com/maps/api/directions/json?origin=";
		$path_url .= urlencode(urldecode($address_start));
		$path_url .= "&alternatives=false&";
		$path_url .= "units=".$unit;
		$path_url .= "&destination=";
		$path_url .= urlencode(urldecode($address_end));
		$path_url .= "&sensor=false";

   		$data = $this->fileGetContents($path_url);

		$json = json_decode(utf8_encode($data),true);

		// Get info
		$distance 			= $json['routes'][0]['legs'][0]['distance']['text']; //Distance in the choose unity 
		$distance_m 		= $json['routes'][0]['legs'][0]['distance']['value']; //Distance in meters
		$duration 			= $json['routes'][0]['legs'][0]['duration']['text']; //Duration
		$duration_s 		= $json['routes'][0]['legs'][0]['duration']['value']; //Duration in seconds
		$end_address 		= $json['routes'][0]['legs'][0]['end_address']; //End Address
		$end_address_lat 	= $json['routes'][0]['legs'][0]['end_location']['lat']; //End Address Latitude
		$end_address_lon 	= $json['routes'][0]['legs'][0]['end_location']['lng']; //End Address Longitude
		$start_address 		= $json['routes'][0]['legs'][0]['start_address']; //End Address
		$start_address_lat 	= $json['routes'][0]['legs'][0]['start_location']['lat']; //End Address Latitude
		$start_address_lon 	= $json['routes'][0]['legs'][0]['start_location']['lng']; //End Address Longitude


		// Return array of Info
		return compact( 'distance', 'distance_m', 'duration', 'duration_s', 'end_address', 'end_address_lat', 'end_address_lon', 'start_address', 'start_address_lat', 'start_address_lon' );
	}
    
    function getMapInfo ($map_info, $field, $name_type = 'long_name') {
        foreach ($map_info as $map) {
            if (isset($map['types']) && ($map['types'][0] == $field)) {
                return $map[$name_type];
            }
        }
        return null;
    }

    // Geocode an address: return array of latitude & longitude
    function gmapGeocode( $address ) {

            // Make Google Geocoding API URL
            $path_url = 'http://maps.google.com/maps/api/geocode/json?address=';
            $path_url .= urlencode( $address ).'&sensor=false';

            $data = $this->fileGetContents($path_url);

            $json = json_decode(utf8_encode($data),true);
                    
            //Distance in the choose unity 
            // Get info
            $lat 		    = $json['results'][0]['geometry']['location']['lat']; //latitude
            $long 		    = $json['results'][0]['geometry']['location']['lng']; //longitude
            
            $address_components = $json['results'][0]['address_components'];
            $number         = $this->getMapInfo($address_components,'street_number');
            $street         = $this->getMapInfo($address_components,'route');
            if (!$street) $street = $this->getMapInfo($address_components,'establishment');
            $city           = $this->getMapInfo($address_components,'locality');
            $neighborhood   = $this->getMapInfo($address_components,'neighborhood');
            $county         = $this->getMapInfo($address_components,'administrative_area_level_2');
            $state          = $this->getMapInfo($address_components,'administrative_area_level_1');
            $state_short    = $this->getMapInfo($address_components,'administrative_area_level_1','short_name');
            $country        = $this->getMapInfo($address_components,'country');
            $country_short  = $this->getMapInfo($address_components,'country','short_name');
            $zip            = $this->getMapInfo($address_components,'postal_code');
            $complete		= $json['results'][0]['formatted_address']; //Complete Address
            
            // Return array of Info
            return compact( 'lat', 'long', 'number', 'street', 'neighborhood', 'city', 'county', 'state', 'state_short', 'country', 'country_short', 'zip','complete' );
    }
    
    //Get Valid Car on DB    
    function availableCar($distance_travel) {
        $model = new p2p_bp_ModelUser();
        
        $car = $model->getCars('ORDER BY p2p_bp_cars_name');
        
        $cars_show = '';
        
        //Get Option
        $distance_price = get_option('distance');
        $system = get_option('select_distance');
        
        //Define basic distances
        $kms = self::DIST_KM; $miles = self::DIST_MILE;
        
        $index = 0;
        $totalCar = count($car);
        for ($i=0;$i<$totalCar;$i++) {
            $less_than = $car[$i]->p2p_bp_cars_value_lower;
            $more_than = $car[$i]->p2p_bp_cars_value_higher;
            if ($car[$i]->p2p_bp_cars_enabled == 1) {
                if (is_numeric($less_than) && is_numeric($more_than)) {
                    $price = ($distance_travel/$$system <= $distance_price)?($distance_travel/$$system) * $less_than:($distance_travel/$$system) * $more_than;
                    $cars_show[$index]['price'] = (($price >= $car[$i]->p2p_bp_cars_min)?round($price,2):$car[$i]->p2p_bp_cars_min)+get_option('increase_ride');
                    $cars_show[$index]['id'] = $car[$i]->p2p_bp_cars_id;
                    $cars_show[$index]['bodytype'] = $car[$i]->p2p_bp_cars_name;
                    $cars_show[$index]['passenger'] = $car[$i]->p2p_bp_cars_passenger;
                    $cars_show[$index]['luggage'] = $car[$i]->p2p_bp_cars_luggage;
                    $cars_show[$index]['pic'] = $car[$i]->p2p_bp_cars_pic;
                    $index++;
                }
            }
        }	
        return $cars_show;
    }
    
    function getTravelPrice($start, $end, $carId) {
        $model = new p2p_bp_ModelUser();
        $car = $model->getCar($carId);

        //Get Option
        $distance_price = get_option('distance');
        $system = get_option('select_distance');

        //Find Distance
        $distance = $this->gmapDistance($start, $end, $system);
        $distance_travel = $distance['distance_m'];
        
        //Get Basic Information
        $less_than = $car->p2p_bp_cars_value_lower;
        $more_than = $car->p2p_bp_cars_value_higher;
        
        //Define basic distances
        $kms = self::DIST_KM; $miles = self::DIST_MILE;
    
        $price = ($distance_travel/$$system <= $distance_price)?($distance_travel/$$system) * $less_than:($distance_travel/$$system) * $more_than;
        $price = (($price >= $car->p2p_bp_cars_min)?round($price,2):$car->p2p_bp_cars_min)+(get_option('increase_ride'));
        
        return number_format($price, 2, '.', '');
    }

    function createLink($car, $start, $end) {
        require_once( '../../../../../wp-blog-header.php' );

    	(strpos(get_option('reservation_page'),'?')!==false)?$division='&':$division='?';
        
        $link = get_option('reservation_page').$division."car={$car}&start={$start}&end={$end}";

        return $link;
    }

/********************************************************/
/* FUNCTIONS REGISTRATION 
********************************************************/
   
    function showReservation() {
        //Call View
        include_once (dirname(__FILE__)."/../view/reservation.php");

    }
     
    function startDiv($width = 100, $side = 'left') {
        $output = '';
        $output .= '<div class="w'.$width.'p '.$side.' h40 pos0">';
        
        return $output;	
    }

    function endDiv() {
        $output = '';
        $output .= '</div>';
        
        return $output;	
    }

    
    function createLabel($title, $text, $class = '') {
        $output = '';
        $output .= '<span class="left">'.$title.':</span>';	
        $output .= '<span class="left '.$class.'">'.$text.'</span>';	
        
        return $output;
    }
    
    function createInput($textcol, $namecol, $valuecol, $icon, $error = 0, $class = '') {
	
        (isset($_POST[$namecol]))?$valuecol = $_POST[$namecol]:$valuecol='';
        $class_error = ($error == 1)?'error_form':'';
    
        $output = '';
        $output .= '	<div class="w100p right">';
        $output .= '		<div class="p2p_bp_input-group margin-bottom-sm w100p '.$class.'">';
        $output .= '			<span class="p2p_bp_input-group-addon '.$class_error.' p2p_bp_glyphicon p2p_bp_glyphicon-'.$icon.' pos1l"></span>';
        
        if (get_option('placeholder')) {
            $output .= '			<input class="p2p_bp_form-control pos1 '.$class_error.'" type="text" name="'.$namecol.'" id="'.$namecol.'" value="'.$valuecol.'" placeholder="'.$textcol.'" class="w100p">';
        } else {
            $output .= '			<span class="p2p_bp_input-group-addon pos1 pos1l '.$class_error.'">'.$textcol.'</span>';        
            $output .= '			<input class="p2p_bp_form-control pos1 '.$class_error.'" type="text" name="'.$namecol.'" id="'.$namecol.'" value="'.$valuecol.'" class="w100p">';        
        }
        
        
        $output .= '		</div>';
        $output .= '	</div>';
    
        
        return $output;
    }

    function createText($textcol, $namecol, $valuecol) {
        $output = '';
        $output .= '	<div class="w30p left">'.$textcol.':</div>';
        $output .= '	<div class="w70p right"><span id="tx_'.$namecol.'">'.$valuecol.'</span><input type="hidden" name="'.$namecol.'" value="'.$valuecol.'" /></div>';
        
        return $output;
    }

    function createSelect($textcol, $namecol, $start, $end, $use_zero, $icon = false, $error = false) {
        $class_error = ($error == 1)?'error_form':'';
        $output = '';
        $output .= '	<span class="left">'.$textcol.':</span>';
        $output .= '	<span class="left">';
        $output .= $this->createDropDown($namecol, $start, $end, $use_zero, $icon, $class_error);
        $output .= '	</span>';
            
        return $output;
    }
    
   function createDropDown ($namecol, $start, $end, $use_zero = false, $icon = false, $class_error = false) {
        $output = '';
        
        if ($icon != false) {
            $output .= '<div class="p2p_bp_input-group margin-bottom-sm">';
            $output .= '<span class="p2p_bp_input-group-addon fa fa-'.$icon.' pos1l '.$class_error.'"></span>';
            $output .= '<select class="p2p_bp_form-control pos1 left" name="'.$namecol.'" id="'.$namecol.'">';
        } else {
            $output .= '<select class="left" name="'.$namecol.'" id="'.$namecol.'">';
        }
        
        for ($i=$start;$i<=$end;$i++) {
            $zero='';
            (isset($_POST[$namecol]) && ($i == $_POST[$namecol]))?$selected = 'selected':$selected = '';
            (strlen($i) < 2 && $use_zero)?$zero='0':$zero='';
            $output .= '	<option value="'.$i.'"  '.$selected.'>'.$zero.$i.'</option>';
        }
        $output .= '</select>';
        
        if ($icon != false)
            $output .= '</div>';
            
        return $output;	
    }
    
    function selectService($value, $error) {
        $model = new p2p_bp_ModelUser();
        $services = $model->getServices();
        
        ($error == 1)?$class_error = 'error_form':$class_error='';
        
        $output = $this->startDiv();
        
        if ($services != 0) {
            $output .= '<span class="left">Type of Service: </span>';
            $output .= '<div class="p2p_bp_input-group margin-bottom-sm w45p">';
            $output .= '<span class="p2p_bp_input-group-addon fa fa-list pos1l '.$class_error.'"></span>';
            //$output .= '<span class="left">';
            $output .= '<select class="p2p_bp_form-control pos1  '.$class_error.' left" id="servicetype" name="servicetype">';
            $output .= '<option value="0" selected="selected">----- Select a Service -----</option>';
            foreach ($services as $service) {
                ($value == $service->p2p_bp_service_name)?$selected='selected':$selected='';
                $output .= '<option value="'.$service->p2p_bp_service_name.'" '.$selected.'>'.$service->p2p_bp_service_name.'</option>';            
            }
            
            if (get_option('service_others')) {
                $output .= '<option value="Other">Other</option>';                        
            }
            $output .= '</select>';
            //$output .= '</span>';
            $output .= '</div> ';
        } else {
            $output .= 'There is no Service Option';
            $output .= '<input type="hidden" name="servicetype" VALUE="">';
        }
        
        $output .=$this->endDiv();

        return $output;
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
    function sendMailMessage($info, $to = 'admin') {
        
        $output = '';
        $output .= file_get_contents(dirname(__FILE__).'/../view/mail/mail_template_header.html');
        $output .= ($to == 'admin')?file_get_contents(dirname(__FILE__).'/../view/mail/mail_template.html'):file_get_contents(dirname(__FILE__).'/../view/mail/mail_template_client.html');
        
        $output = (get_option('p2p_payment_type') == 'no' && get_option('p2p_nopayment_creditcard') && $to == 'admin')?str_replace("%card_info%", 'Card Information: '.$info['card_info'], $output):str_replace("%card_info%", "", $output);
        
        $info['company_name'] = get_option('p2p_company_name');
        $info['company_phone'] = get_option('p2p_company_phone');
        $info['company_owner'] = get_option('p2p_company_owner');
        
        $info['p_time_h'] = (strlen($info['p_time_h']) < 2)?'0'.$info['p_time_h']:$info['p_time_h'];
        $info['p_time_m'] = (strlen($info['p_time_m']) < 2)?'0'.$info['p_time_m']:$info['p_time_m'];
        $info['r_p_time_h'] = (strlen($info['r_p_time_h']) < 2)?'0'.$info['r_p_time_h']:$info['r_p_time_h'];
        $info['r_p_time_m'] = (strlen($info['r_p_time_m']) < 2)?'0'.$info['r_p_time_m']:$info['r_p_time_m'];
        
        if ($info['r'] == 1) {
            $fields = array('r_p_date','r_p_time_h','r_p_time_m','r_p_instructions');
            $output .= file_get_contents(dirname(__FILE__).'/../view/mail/mail_template_round_trip.html');
            foreach ($fields as $field) {
                if (isset($info[$field])) $output = str_replace("%{$field}%", $info[$field], $output);
            }
        }
        
        $output .= file_get_contents(dirname(__FILE__).'/../view/mail/mail_template_footer.html');
        
        $fields = array('first_name','last_name','phone','email','npassenger','nluggage','vehicletype','servicetype','p_address','p_apt','p_city','p_state','p_zip','p_date','p_time_h','p_time_m','p_instructions','d_address','d_apt','d_city','d_state','d_zip','trip','final_price','gratuity','company_name','company_phone','company_owner');        
        foreach ($fields as $field) {
            if (isset($info[$field])) $output = str_replace("%{$field}%", $info[$field], $output);
        }
        
        return $output;
    }
    
    function sendMail ($info, $to = 'admin') {
        $subject = ($to == 'admin')?"You got a new Reservation":'"About your reservation';
        $message = $this->sendMailMessage($info, $to);
        
        // SMTP email sent
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
        $phpmailer = new PHPMailer();

        $phpmailer->SMTPDebug 	= (get_option('p2p_email_debug'))?2:0; //0 for release; 2 for debug

        $phpmailer->Username  	= get_option('p2p_email');
        $phpmailer->Password  	= get_option('p2p_pass');
        
        if ($to == 'admin') {
            $phpmailer->AddAddress(get_option('p2p_email'), get_option('p2p_company_name'));
            if ((get_option('p2p_email') != get_option('admin_email')) && (get_option('p2p_email_admin'))) $phpmailer->AddAddress(get_option('admin_email'), 'Admin');
            if (get_option('email_receive_1')) $phpmailer->AddAddress(get_option('email_receive_1'), get_option('email_receive_name_1'));
            if (get_option('email_receive_2')) $phpmailer->AddAddress(get_option('email_receive_2'), get_option('email_receive_name_2'));
            if (get_option('email_receive_3')) $phpmailer->AddAddress(get_option('email_receive_3'), get_option('email_receive_name_3'));
        } else {$phpmailer->AddAddress($info['email'], $info['first_name'].' '.$info['last_name']);}
        
        $phpmailer->IsSMTP(); // telling the class to use SMTP

        if (strtolower(get_option('p2p_smtpsecure')) != 'no') $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure	= get_option('p2p_smtpsecure');
        $phpmailer->Host        = get_option('p2p_host'); // SMTP server
        $phpmailer->Port	    = get_option('p2p_port');
        
        $phpmailer->Subject     = $subject;
        $phpmailer->Body        = $message;	//HTML Body
        if ($to == 'admin') $phpmailer->SetFrom($info['email'],$info['first_name'].' '.$info['last_name']);
        else $phpmailer->SetFrom(get_option('p2p_email'),get_option('p2p_company_name'));
        $phpmailer->MsgHTML($message);
        $phpmailer->Priotity	= 1;
        $phpmailer->AltBody     = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        
        $result = '';
        if ($phpmailer->Send()) {
            $result['sent'] = true;
            $result['error'] = '';
        } else {
            $result['sent'] = false;
            $result['error'] = $phpmailer->ErrorInfo;        
        }
        
        return $result;
    }
    
    /********************************************************/
    /* PAYMENT
    ********************************************************/

    //BrainTree Payment
    function paymentCallBrainTree() {
        $output = '';
        $output .= '<script src="'.plugins_url('p2p_bigpromoter/system/payment/braintree/').'braintree.js"></script>'.PHP_EOL;
        $output .= '<script>'.PHP_EOL;
        $output .= 'var braintree = Braintree.create("'.get_option('p2p_braintree_config_code').'");'.PHP_EOL;
        $output .= 'braintree.onSubmitEncryptForm("braintree-payment-form");'.PHP_EOL;
        $output .= '</script>'.PHP_EOL;
        
        return $output;
    }
    
    function paymentBrainTree($price, $info) {
        require_once (dirname(__FILE__).'/../../system/payment/braintree/lib/Braintree.php');
    
        Braintree_Configuration::environment(get_option('p2p_braintree_enviroment'));
        Braintree_Configuration::merchantId(get_option('p2p_braintree_merchantId'));
        Braintree_Configuration::publicKey(get_option('p2p_braintree_publicKey'));
        Braintree_Configuration::privateKey(get_option('p2p_braintree_privateKey'));
        
        $desc = ($info['r'])?' (Round Trip)':'';
         
        $paymentInfo = array('amount' => $price,
                            'type' => Braintree_Transaction::SALE,
                            'customer' => array(
                                                    'firstName' => $info['first_name'],
                                                    'lastName' => $info['last_name'],
                                                    'phone' => $info['phone'],
                                                    'email' => $info['email']
                                                ),
                            'creditCard' => array(
                                                    'number' => str_replace(' ','',$info['card_num']),
                                                    'cvv' => $info['card_cvv'],
                                                    'expirationMonth' => $info['card_month'],
                                                    'expirationYear' => $info['card_year']
                                                )
                        );
         
        $output[3] = $paymentInfo;
        $result = Braintree_Transaction::sale($paymentInfo);
        $output[0] = false;
        $output[1] = ''; //Message
        $output[2] = 0; //Value
        $output[3] = ''; //Transaction Id
        $output[4] = 'BrainTree'; //Company
        if ($result->success) {
            $output[0] = true;
            $output[1] .= "<div class='alert p2p_bp_alert-success'>";
            $output[1] .= "We got your Payment on BrainTree! [Transaction code: ". $result->transaction->id."]";
            $output[1] .= "Amount Paid: ".get_option('select_currency')."{$price}".$desc;
            $output[1] .= "</div>";
            $output[2] = $value;
            $output[3] = $result->transaction->id;
        } else if ($result->transaction) {
            $output[1] .= "<div class='alert p2p_bp_alert-danger'>";
            $output[1] .= "<BR>Error processing transaction:";
            $output[1] .= "<BR>  message: " . $result->message;
            $output[1] .= "<BR>  code: " . $result->transaction->processorResponseCode;
            $output[1] .= "<BR>  text: " . $result->transaction->processorResponseText;
            $output[1] .= "</div>";
        } else {
            $output[1] .= "<div class='alert p2p_bp_alert-danger'>";
            $output[1] .= "Validation errors on your Payment: <BR>";            
            foreach (($result->errors->deepAll()) as $error) {
                $output[1] .= "- " . $error->message . "<br/>";
            }                    
            $output[1] .= "<BR>";
            $output[1] .= "Your reservation FAILED! Please Contact Us!";
            $output[1] .= "</div>";
        }
        
        return $output;
    }
    //End BrainTree
    
    //Paypal Payment
    function paymentPayPal($price, $info) {
        require dirname(__FILE__).'/../../system/payment/paypal/bootstrap.php';
        
        // ### CreditCard
        // A resource representing a credit card that can be
        // used to fund a payment.
        $card = new CreditCard();
        $card->setType($info['cardtype'])
            ->setNumber($info['card_num'])
            ->setExpireMonth($info['card_month'])
            ->setExpireYear($info['card_year'])
            ->setCvv2($info['card_cvv'])
            ->setFirstName($info['first_name'])
            ->setLastName($info['last_name']);
        
        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For direct credit card payments, set the CreditCard
        // field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCard($card);
        
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For direct credit card payments, set payment method
        // to 'credit_card' and add an array of funding instruments.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));
        
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        
        //If Round Trip charge double
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($price);
        
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it.
        ($info['r'])?$desc=' (Round Trip)':$desc='';
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("Transport - From{$info['p_address']} to {$info['d_address']}".$desc);
        
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));
        
        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        $output[0] = false; //Result of Payment
        $output[1] = ''; //Message
        $output[2] = 0; //Value
        $output[3] = ''; //Transaction ID
        $output[4] = 'PayPal';
        try {
            $result = $payment->create($apiContext);
            $output[0] = true;
            $output[1] .= "<div class='alert p2p_bp_alert-success'>";
            $output[1] .= "We got your Payment on PayPal! [Transaction code: ". $result->id."]<BR>";
            $output[1] .= "Amount Paid: ".get_option('select_currency')."{$price}".$desc;
            $output[1] .= "</div>";
            $output[2] = $price;
            $output[3] = $result->id;
        } catch (PayPal\Exception\PPConnectionException $ex) {
            $error = explode('"',$ex->getData());
            $output[1] .= "<div class='alert p2p_bp_alert-danger'>";
            $output[1] .= "Validation errors on your Payment: <BR>";
            $output[1] .= "Error:".$error[9]."<BR>";
            $output[1] .= "Message:".$error[13]."<BR>";
            $output[1] .= "<BR>";
            $output[1] .= "Your reservation FAILED! Please Contact Us!";
            $output[1] .= "</div>";
        }
        
        return $output;

    }
    
    function selectCartType($value) {
        
        $cardType = array('visa','mastercard','amex','discover');
        $output = $this->startDiv();
        
        $output .= '<div class="p2p_bp_input-group margin-bottom-sm w100p">';
        $output .= '<span class="p2p_bp_input-group-addon fa fa-list pos1l"></span>';
        $output .= '<span class="left">';
        $output .= '<select class="p2p_bp_form-control pos1l left" id="cardtype" name="cardtype">';
        $output .= '<option value="0" selected="selected">[Choose your Card]</option>';
        foreach ($cardType as $card) {
            ($value == $card)?$selected='selected':$selected='';
            $output .= '<option value="'.$card.'" '.$selected.'>'.ucfirst($card).'</option>';            
        }
        $output .= '</select>';
        $output .= '</span>';
        $output .= '</div> ';
        
        $output .= $this->endDiv();

        return $output;
    }    

    function doReservation($info, $chooseCar, $price) {
        if (get_option('p2p_calendar_enabled')) { require dirname(__FILE__).'/../../system/calendar/calendar.php'; }
        $model = new p2p_bp_ModelUser();
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
        
        $final_price = (isset($_POST['r']) && ($_POST['r'] == 1))?$price*2:$price;
        $er['gratuity'] = false;
        $info['trip'] = $final_price;
        $info['gratuity'] = 0;
        if (isset($_POST['gratuity']) && (get_option('insert_gratuity'))) {
            if (is_numeric($_POST['gratuity'])) {
                if ($_POST['gratuity'] < 0) {$error++; $er['gratuity'] = true;}
                $final_price = round(($final_price * (1 + ($_POST['gratuity']/100))),2);
            } else {
                if (isset($_POST['gratuityOther_input']) && is_numeric($_POST['gratuityOther_input'])) {
                    if ($_POST['gratuityOther_input'] < 0) {$error++; $er['gratuity'] = true;}
                    $final_price = $final_price + $_POST['gratuityOther_input'];
                }
            }
        }
        $info['gratuity'] = $final_price - $info['trip'];
        $info['final_price'] = $final_price;
        
        //End Check
        if (!($error)) { //If there is no Error
            // Check if reservation was already made
            if ($model->checkIfAlreadyMade($_POST)) {
                echo '<div class="alert p2p_bp_alert-warning">We already have your reservation!</div>';
            } else {
                $payment_info = '';
                $info['send_cardinfo'] = (get_option('p2p_payment_type') == 'no' && get_option('p2p_nopayment_creditcard'))?true:false;
                if (get_option('p2p_payment_type') == 'braintree') {
                    $payment = $this->paymentBrainTree($final_price,$info);
                    echo $payment[1]; //Print Payment return
                    $payment_info['paid'] = $payment[2];
                    $payment_info['id'] = $payment[3];
                    $payment_info['company'] = $payment[4];
                } else if (get_option('p2p_payment_type') == 'paypal') {
                    $payment = $this->paymentPayPal($final_price,$info);
                    echo $payment[1]; //Print Payment return
                    $payment_info['paid'] = $payment[2];
                    $payment_info['id'] = $payment[3];
                    $payment_info['company'] = $payment[4];
                } else {
                    $payment[0] = true;
                    $payment_info['paid'] = $final_price;
                    $payment_info['id'] = 'No Payment';
                    $payment_info['company'] = 'No Payment';
                    if ($info['send_cardinfo']) {
                        $info['card_info'] = "(".$info['cardtype'].") ".$info['card_num']." cvv: ".$info['card_cvv']." expire: ".$info['card_month']."/".$info['card_year'];
                    }
                }
                
                if ($payment[0]) { //If Payment is OK
                    if (get_option('p2p_calendar_enabled')) {
                        $insertCalendar = new GoogleCalendar();
                        if ($info['r']) {
                            $resultCalendarRoundTrip = $insertCalendar->insert_event($info, $chooseCar, $payment_info, 0, 1, 1);
                            $resultCalendarRoundTrip = $insertCalendar->insert_event($info, $chooseCar, $payment_info, 0, 1, 2);
                        } else {
                            $resultCalendar = $insertCalendar->insert_event($info, $chooseCar, $payment_info);
                        }
                    }
                    $insert = $model->insertReservation($info, $payment_info);
                    if ($insert) {
?>
                        <div class="alert p2p_bp_alert-success">We got your reservation!</div>
<?php
                        //Send Mail
                        $sendMail = $this->sendMail($info);
                        $sendMail_Client = $this->sendMail($info,'client');
                        if(!$sendMail['sent']) { //Check if e-mail was sent
                            if ($sendMail['error'] != 0) {
                                echo "Mailer Error: " . $sendMail['error'];
                            }
                        }
                    } 
                }
            }
        } else { //If there is Error, show them
            if ($er['first_name']) echo '<div class="alert p2p_bp_alert-danger">You must fill your <strong>First Name</strong>!</div>';
            if ($er['last_name']) echo '<div class="alert p2p_bp_alert-danger">You must fill your <strong>Last Name</strong>!</div>';
            if ($er['email']) echo '<div class="alert p2p_bp_alert-danger">You must fill a valid <strong>E-mail</strong>!</div>';
            if ($er['npassenger']) echo '<div class="alert p2p_bp_alert-danger">The field <strong>Passengers</strong> must be numeric!</div>';
            if ($er['nluggage']) echo '<div class="alert p2p_bp_alert-danger">The field <strong>Luggage</strong> must be numeric!</div>';
            if ($er['servicetype']) echo '<div class="alert p2p_bp_alert-danger">You must select a valid <strong>Service</strong>!</div>';
            if ($er['p_date']) echo '<div class="alert p2p_bp_alert-danger">You must fill a valid <strong>Pick-Up Date</strong>!</div>';
            if ($er['r_p_date']) echo '<div class="alert p2p_bp_alert-danger">You must fill a valid <strong>Round-Trip Pick-up Date</strong>!</div>';
            if ($er['gratuity']) echo '<div class="alert p2p_bp_alert-danger"><strong>Gratuity</strong> value cannot be less than 0</div>';
        }
        
        return $er;
    }    
/********************************************************/
/* END REGISTRATION 
********************************************************/

    function color () {
        $output = '';
        $output .= '<style type="text/css">'.PHP_EOL;
        $output .= get_option('p2p_custom_css').PHP_EOL;
        if (get_option('p2p_color') == 1) {
            //Change Label
            $output .= '.p2p_bp_input-group-addon {'.PHP_EOL;
            $output .= '    color: '.get_option('p2p_label_color').';'.PHP_EOL;
            $output .= '    background: '.get_option('p2p_label_background').';'.PHP_EOL;
            if (get_option('p2p_label_border') == 1) 
                $output .= '    border: 1px solid '.get_option('p2p_label_border_color').';'.PHP_EOL;
            else
                $output .= '    border: 0px;';
            $output .= '}'.PHP_EOL;
            //Change Input
            $output .= '.p2p_bp_input-group .p2p_bp_form-control {'.PHP_EOL;
            $output .= '    color: '.get_option('p2p_input_color').';'.PHP_EOL;
            $output .= '    background: '.get_option('p2p_input_background').';'.PHP_EOL;
            if (get_option('p2p_input_border') == 1) 
                $output .= '    border: 1px solid '.get_option('p2p_input_border_color').';'.PHP_EOL;
            else
                $output .= '    border: 0px;';
            $output .= '}'.PHP_EOL;
            //Change Button
            $output .= '.p2p_bp_btn-default {'.PHP_EOL;
            $output .= '    color: '.get_option('p2p_button_color').';'.PHP_EOL;
            $output .= '    background: '.get_option('p2p_button_background').';'.PHP_EOL;
            if (get_option('p2p_button_border') == 1) 
                $output .= '    border: '.get_option('p2p_button_border_color').';'.PHP_EOL;
            else
                $output .= '    border: 0px;';
            $output .= '}'.PHP_EOL;
        } 
        $output .= '</style>'.PHP_EOL;
        return $output;
    }
}
?>