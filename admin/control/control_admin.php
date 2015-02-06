<?php
class p2p_bp_ControlAdmin {
    
    function __construct() {
        $this->colorSpace = '&nbsp;&nbsp;&nbsp;&nbsp;';
    }  
    
    //Create Select to Currency and Distance
    function selectArray($name, $values, $active='', $class='') {
        $output = "";
        $output .= "<select name='{$name}' id='{$name}' class='{$class}'>";
        foreach ($values as $v) {
                if ($v == $active) $output .= "<option value='{$v}' selected>{$v}</option>";
                else $output .=  "<option value='{$v}'>{$v}</option>";
        }
        $output .= "</select>";
        
        return $output;
    }
    
    //Check if Setting was Updated
    function checkUpdate($result) {
        if ($result == true) {
            echo '<div id="divUpdate" class="alert p2p_bp_alert-success">Success: Settings Updated!</div>';
        } else if ($result == false && isset($result)) {
            echo '<div id="divUpdate" class="alert p2p_bp_alert-danger">Failed: Settings NOT Updated!</div>';
        }
    }
    
    /*****************************************************/
    // MANAGE FLEET FUNCTION
    /*****************************************************/
    
    //Check if all information is correct
 	function checkCarInfo($info) {
		$erro = '';
		
		if (strlen($info['body']) < 3) $erro .= '[Body] must have more than 3 character<BR>';
		if (!is_numeric($info['min'])) $erro .= '[Minimum] must be numeric<BR>';
		if (!is_numeric($info['passenger'])) $erro .= '[Passenger] must be numeric<BR>';
		if (!is_numeric($info['luggage'])) $erro .= '[Luggage] must be numeric<BR>';		
		if ((!is_float($info['more_than'])) && (!is_numeric($info['more_than'])))
			$erro .= '[Higher Than] must be numeric<BR>';				
		if ((!is_float($info['less_than'])) && (!is_numeric($info['less_than'])))
			$erro .= '[Lower Than] must be numeric<BR>';				
		
		if (strlen($erro) <= 0) {return false;}
		else {return $erro;}
	}   
    
    //Include car on table
 	function addCar () {
    
		require_once( '../../../../../../wp-blog-header.php' );
        
        $model = new p2p_bp_ModelAdmin();

		$check = $this->checkCarInfo($_POST);

        if (!$check) {
			$insert = $model->insertCar($model->fleet['table'], $model->fleet['prefix'], $_POST);
			return false;
		} else { return $check; }
	}   
    
    //Update car on table
 	function editCar () {
    
		require_once( '../../../../../../wp-blog-header.php' );

		$model = new p2p_bp_ModelAdmin();
		
		$check = $this->checkCarInfo($_POST);
		
		if (!$check) {
			$model->editCar($model->fleet['table'], $model->fleet['prefix'], $_POST);
			return false;
		} else { return $check; }	
	}

    //Delete car on table
    function deleteCar ($delete = false) {
		
		require_once( '../../../../../../wp-blog-header.php' );
		
        $model = new p2p_bp_ModelAdmin();
		
		if ($delete) {
			$model->deleteCar($model->fleet['table'], $model->fleet['prefix'], $_POST['id']);
			return true;
		} else {
            return false;
		}
	}   
    //END MANAGE FLEET
    
    /*****************************************************/
    // MANAGE FLEET FUNCTION
    /*****************************************************/
    
    //Check if all information is correct
 	function checkServiceInfo($info) {
		$erro = '';
		
		if (strlen($info['service']) < 3) $erro .= '[Service] must have more than 3 character<BR>';
		
		if (strlen($erro) <= 0) {return false;}
		else {return $erro;}
	}    
    //Include Service on table
 	function addService () {
    
		require_once( '../../../../../../wp-blog-header.php' );
        
        $model = new p2p_bp_ModelAdmin();

		$check = $this->checkServiceInfo($_POST);

        if (!$check) {
			$insert = $model->insertService($model->service['table'], $model->service['prefix'], $_POST);
			return false;
		} else { return $check; }
	}

    //Update car on table
 	function editService () {
    
		require_once( '../../../../../../wp-blog-header.php' );

		$model = new p2p_bp_ModelAdmin();
		
		$check = $this->checkServiceInfo($_POST);
		
		if (!$check) {
			$model->editService($model->service['table'], $model->service['prefix'], $_POST);
			return false;
		} else { return $check; }	
	}
   //Delete car on table
    function deleteService ($delete = false) {
		
		require_once( '../../../../../../wp-blog-header.php' );
		
        $model = new p2p_bp_ModelAdmin();
		
		if ($delete) {
			$model->deleteService($model->service['table'], $model->service['prefix'], $_POST['id']);
			return true;
		} else {
            return false;
		}
	}   
    //END MANAGE SERVICE
    
    /*****************************************************/
    // MAPS FUNCTION
    /*****************************************************/
    
    // The shortcode callback
    function generateMap($attr, $address) {
            // Set map default
            $defaults = array(
                    'width' => '500',
                    'height' => '500',
                    'zoom' => 16,
            );

            // Get map attributes (set to defaults if omitted)
            extract(shortcode_atts( $defaults, $attr) );
            
            // get coordinates
            $coord = $this->gmapGeocode($address);

            // Make sure we have coordinates, otherwise return empty string
            if(!$coord) return '';

            // Put Coords on retunr
            $output['lat'] = $coord['lat'];
            $output['long'] = $coord['long'];

            // Output for the shortcode
            $output['script'] = '';

            // populate $lat and $long variables
            extract( $coord );

            // Sanitize variables depending on the context they will be printed in
            $lat = esc_js( $lat );
            $long = esc_js( $long );
            $address = esc_js( $address );
            $zoom = esc_js( $zoom );
            $width = esc_attr( $width );
            $height = esc_attr( $height );

            // Add the Google Maps main javascript only once per page
            static $script_added = false;
            if( $script_added == false ) {
                    $output['script'] .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"> </script> ';
                    $script_added = true;
            }
            // Add the map specific code
            $output['script'] .= <<<CODE
                    <div id="bp_map" > </div>
                    <script type="text/javascript">                           
                            bp_generate_map({$lat},{$long},{$zoom});
                    </script>
                    <style type"text/css">
                            #bp_map {
                                    max-width: {$width};
                                    height: {$height}; 
                            }
                    </style>
CODE;
            return $output;
    }
    
    //Access file
    function fileGetContents( $site_url ){
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
            $number 		= $json['results'][0]['address_components'][0]['long_name']; //Street Number
            $street 		= $json['results'][0]['address_components'][1]['long_name']; //Street Name
            $neighborhood 	= $json['results'][0]['address_components'][2]['long_name']; //Neighborhood Name
            $city 		    = $json['results'][0]['address_components'][3]['long_name']; //City Name
            /*
            $county 		= $json['results'][0]['address_components'][4]['long_name']; //County Name
            $state 		    = $json['results'][0]['address_components'][5]['long_name']; //State Name
            $state_short 	= $json['results'][0]['address_components'][5]['short_name']; //State Name Short
            $country 		= $json['results'][0]['address_components'][6]['long_name']; //Country Name
            $country_short 	= $json['results'][0]['address_components'][6]['short_name']; //Country Name Short
            $zip 		    = $json['results'][0]['address_components'][7]['long_name']; //Zip Code
            */
            $complete		= $json['results'][0]['formatted_address']; //Complete Address

            // Return array of Info
            return compact( 'lat', 'long', 'number', 'street', 'neighborhood', 'city', /*'county', 'state', 'state_short', 'country', 'country_short', 'zip',*/ 'complete' );
    }
    //END MAP
    
    function reverseDate($date) {
        $d = explode('-',$date);
        if (count($d) < 3) return false;
        $newD = $d[1].'/'.$d[2].'/'.$d[0];
        return $newD;
    }

        
    function dbDate($date) {
        $d = explode('/',$date);
        if (count($d) < 3) return false;
        $newD = $d[2].'-'.$d[0].'-'.$d[1];
        return $newD;
    }
    
    function validateDate($date) {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) return true;
        else return false;    
    }
    
    
    function paymentType($type) {
        $result['checked_paypal'] = $result['checked_braintree'] = $result['checked_no'] = '';
        if ($type == 'paypal') {
            $result['checked_paypal'] = 'checked';
            $result['active_paypal'] = 'active';
            $result['display_paypal'] = 'block';
            $result['display_braintree'] = 'none';
        }
        else if ($type == 'braintree') {
            $result['checked_braintree'] = 'checked';
            $result['active_braintree'] = 'active';
            $result['display_paypal'] = 'none';
            $result['display_braintree'] = 'block';
        }
        else {
            $result['checked_no'] = 'checked';
            $result['active_no'] = 'active';
            $result['display_paypal'] = 'none';
            $result['display_braintree'] = 'none';
        }
        return $result;
    }
    
    function getColor($id = false) {
        $color = array(array('0',''),
                       array('1','purple'),
                       array('2','ligthgreen'),
                       array('3','pink'),
                       array('4','ligthred'),
                       array('5','yellow'),
                       array('6','orange'),
                       array('7','cyan'),
                       array('8','gray'),
                       array('9','blue'),
                       array('10','green'),
                       array('11','red'));
        
        if (!$id) return $color;
        else return $color[$id];
    }

    function createSelectColor($id, $change, $trigger, $value) {
    
        $color = $this->getColor($value);
        
        $output = '';
        $output .= '<input type="hidden" id="'.$id.'" value="'.$color[0].'" />'.PHP_EOL;
        $output .= '<div class="p2p_bp_btn-group">'.PHP_EOL;
        $output .= '<button type="button" class="p2p_bp_btn p2p_bp_btn-default dropdown-toggle" data-toggle="dropdown">'.PHP_EOL;
        $output .= '    <span id="'.$change.'" class="'.$color[1].' colorbox">'.$this->colorSpace.'</span> <span class="caret"></span>'.PHP_EOL;
        $output .= '</button>'.PHP_EOL;
        $output .= '<ul class="p2p_bp_dropdown-menu dropdown-color" role="menu" style="padding:10px;">'.PHP_EOL;
        for ($i = 1; $i <= 11; $i++) {
            $colorfor = $this->getColor($i);
            $output .= '    <li style="display: inline-block;"><button type="button" name="'.$colorfor[1].'" class="'.$trigger.' '.$colorfor[1].' p2p_bp_btn p2p_bp_btn-default colorbox" id="'.$i.'"></button></li>'.PHP_EOL;
        }
        $output .=' </ul>'.PHP_EOL;
        $output .= '</div>'.PHP_EOL;
        $output .= '<script>'.PHP_EOL;
        $output .= '     jQuery(".'.$trigger.'").click(function(event) {'.PHP_EOL;
        $output .= '        jQuery("#'.$id.'").val(event.target.id);'.PHP_EOL;
        $output .= '        jQuery("#'.$change.'").removeClass();'.PHP_EOL;
        $output .= '        jQuery("#'.$change.'").addClass(event.target.name);'.PHP_EOL;
       // $output .= 'alert(event.target.name);';
        $output .= '        });'.PHP_EOL;
        $output .= '</script>'.PHP_EOL;
        return $output;
    }    
        
    function createSelectNum($id, $change, $trigger, $value) {
        $output = '';
        $output .= '<input type="hidden" id="'.$id.'" value="'.$value.'" />'.PHP_EOL;
        $output .= '<div class="p2p_bp_btn-group">'.PHP_EOL;
        $output .= '<button type="button" class="p2p_bp_btn p2p_bp_btn-default dropdown-toggle" data-toggle="dropdown">'.PHP_EOL;
        $output .= '    <span id="'.$change.'">'.$value.'</span> <span class="caret"></span>'.PHP_EOL;
        $output .= '</button>'.PHP_EOL;
        $output .= '<ul class="p2p_bp_dropdown-menu" role="menu" style="padding:10px;">'.PHP_EOL;
        for ($i = 1; $i <= 20; $i++) {
            (strlen($i) < 2)?$zero='0':$zero='';
            $output .= '    <li style="display: inline-block;"><button type="button" class="'.$trigger.' p2p_bp_btn p2p_bp_btn-default" id="'.$i.'">'.$zero.$i.'</button></li>'.PHP_EOL;
        }
        $output .=' </ul>'.PHP_EOL;
        $output .= '</div>'.PHP_EOL;
        $output .= '<script>'.PHP_EOL;
        $output .= '     jQuery(".'.$trigger.'").click(function(event) {'.PHP_EOL;
        $output .= '        jQuery("#'.$change.'").html(event.target.id);'.PHP_EOL;
        $output .= '        jQuery("#'.$id.'").val(event.target.id);'.PHP_EOL;
        $output .= '        });'.PHP_EOL;
        $output .= '</script>'.PHP_EOL;
        return $output;
    }
    
    function timeZone($value) {
    $time = array(array('0000','UTC+0'),
                    array('+0020','UTC+0:20'),
                    array('+0030','UTC+0:30'),
                    array('+0100','UTC+1'),
                    array('+0200','UTC+2'),
                    array('+0300','UTC+3'),
                    array('+0330','UTC+3:30'),
                    array('+0400','UTC+4'),
                    array('+0430','UTC+4:30'),
                    array('+0451','UTC+4:51'),
                    array('+0500','UTC+5'),
                    array('+0530','UTC+5:30'),
                    array('+0540','UTC+5:40'),
                    array('+0545','UTC+5:45'),
                    array('+0600','UTC+6'),
                    array('+0630','UTC+6:30'),
                    array('+0700','UTC+7'),
                    array('+0720','UTC+7:20'),
                    array('+0730','UTC+7:30'),
                    array('+0800','UTC+8'),
                    array('+0830','UTC+8:30'),
                    array('+0845','UTC+8:45'),
                    array('+0900','UTC+9'),
                    array('+0930','UTC+9:30'),
                    array('+1000','UTC+10'),
                    array('+1030','UTC+10:30'),
                    array('+1100','UTC+11'),
                    array('+1130','UTC+11:30'),
                    array('+1200','UTC+12'),
                    array('+1245','UTC+12:45'),
                    array('+1300','UTC+13'),
                    array('+1345','UTC+13:45'),
                    array('+1400','UTC+14'),
                    array('-0025','UTC-0:25'),
                    array('-0100','UTC-1'),
                    array('-0200','UTC-2'),
                    array('-0230','UTC-2:30'),
                    array('-0300','UTC-3'),
                    array('-0330','UTC-3:30'),
                    array('-0400','UTC-4'),
                    array('-0430','UTC-4:30'),
                    array('-0500','UTC-5'),
                    array('-0600','UTC-6'),
                    array('-0700','UTC-7'),
                    array('-0800','UTC-8'),
                    array('-0900','UTC-9'),
                    array('-0930','UTC-9:30'),
                    array('-1000','UTC-10'),
                    array('-1100','UTC-11'));
    sort($time);
    $output = '';
    $output .= '<select id="p2p_calendar_timezone" name="p2p_calendar_timezone" class="w100">';
    foreach ($time as $t) {
        if ($t[0] == $value) $output .= "<option value='{$t[0]}' selected>{$t[1]}</option>";
        else $output .=  "<option value='{$t[0]}'>{$t[1]}</option>";
    
    }
    
    $output .= '</select>';
    
    return $output;
    }
    
    function valuesCalendar($name, $button, $field) {
    $values = array(array('first_name','Client: First Name'),
                    array('last_name','Client: Last Name'),
                    array('phone','Client: Phone'),
                    array('email','Client: E-mail'),
                    array('npassenger','Client: N Passenger'),
                    array('nluggage','Client: N Luggage'),
                    array('vehicletype','Client: Vehicle'),
                    array('servicetype','Client: Service'),
                    array('p_address','Pick Up Info: Address'),
                    array('p_apt','Pick Up Info: Apt/Suite'),
                    array('p_city','Pick Up Info: City/State'),
                    array('p_zip','Pick Up Info: Zip Code'),
                    array('p_date','Pick Up Info: Date'),
                    array('p_time_h%:%p_time_m','Pick Up Info: Time'),
                    array('p_instructions','Pick Up Info: Instructions'),
                    array('d_address','Drop Off Info: Address'),
                    array('d_apt','Drop Off Info: Apt/Suite'),
                    array('d_city','Drop Off Info: City/State'),
                    array('d_zip','Drop Off Info: Zip Code'),
                    array('amount_paid','Payment: Amount Paid'),
                    array('transaction_id','Payment: Transaction Id'));
    $output = '';
    $output .= '<select id="'.$name.'" name="'.$name.'" class="w100p" style="margin-bottom: 5px;">';
    foreach ($values as $value) {
        $output .=  "<option value='%{$value[0]}%'>{$value[1]}</option>".PHP_EOL;
    }
    $output .= '</select>'.PHP_EOL;
    $output .= '<div id="'.$button.'" class="w90p p2p_bp_btn p2p_bp_btn-std bgGray">Insert</div>'.PHP_EOL;
    $output .= '<script>'.PHP_EOL;
    $output .= 'jQuery("#'.$button.'").click(function () {'.PHP_EOL;
    $output .= '    var position = jQuery("#'.$field.'").getCursorPosition()'.PHP_EOL;
    $output .= '    var content = jQuery("#'.$field.'").val();'.PHP_EOL;
    $output .= '    var newContent = content.substr(0, position) + jQuery("#'.$name.'").val() + content.substr(position);'.PHP_EOL;
    $output .= '    jQuery("#'.$field.'").val(newContent);'.PHP_EOL;
    $output .= '});'.PHP_EOL;
    $output .= '</script>'.PHP_EOL;
    
    
    return $output;
    }

    
    function borderColor($type, $form) {
        if ($type == '1') {
            $result['checked_yes_'.$form] = 'checked';
            $result['active_yes_'.$form] = 'active';
            $result['display_'.$form] = 'block';
        }
        else {
            $result['checked_no_'.$form] = 'checked';
            $result['active_no_'.$form] = 'active';
            $result['display_'.$form] = 'none';
        }
        return $result;
    }
    
    function createTabs($active) {
    
        $tabs = array('Basic','Calendar','Color','Mail','Map','Payment','BigPromoter');
        
        $output = '';
        $output .= '<ul class="p2p_bp_nav p2p_bp_nav-tabs p2p_bp_nav-justified" role="tablist">';
        //$output .= '<i class="p2p_bp_glyphicon p2p_bp_glyphicon-cog"></i> ';
        
        foreach ($tabs as $tab) {
            $classactive = (strtolower($tab) == strtolower($active))?'active':'';
            $output .= '<li class="'.$classactive.'"><a href="?page=p2p_bigpromoter&tab='.$tab.'">'.$tab.'</a></li>';
        }
        
        $output .= '</ul>';       
                
        return $output;
    }
}
?>