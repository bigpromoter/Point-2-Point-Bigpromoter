<?php
class P2P_Map {
    private $option;
    private $control_util;
	function __construct() {
        $this->option = get_option('p2p_bp');
        
        include_once (P2P_DIR_CONTROL . 'util.php');
        $this->control_util = new P2P_Util();
	}
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
                            generateMap({$lat},{$long},{$zoom});
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

            $data = $this->control_util->fileGetContentsCurl($path_url);

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
    //END MAP
    
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

   		$data = $this->control_util->fileGetContentsCurl($path_url);

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
    
    function getTravelPrice($start, $end, $car) {

        //Get Option
        $distance_price = $this->option['basic']['distance'];
        $system = $this->option['basic']['select_distance'];

        //Find Distance
        $distance = $this->gmapDistance($start, $end, $system);
        $distance_travel = $distance['distance_m'];
        
        //Get Basic Information
        $less_than = $car['lessThan'];
        $more_than = $car['moreThan'];
        
        //Define basic distances
        $kms = DISTANCE_KM; $miles = DISTANCE_MILE;
    
        $price = ($distance_travel/$$system <= $distance_price)?($distance_travel/$$system) * $less_than:($distance_travel/$$system) * $more_than;
        $price = (($price >= $car['minimum'])?round($price,2):$car['minimum'])+(get_option('p2p_bp_fleet_increase_ride'));
        
        return number_format($price, 2, '.', '');
    }
}
?>