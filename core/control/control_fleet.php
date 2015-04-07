<?php
class P2P_Fleet {
    private $fleet;
    private $services;
    private $option;
	function __construct() {
        $this->fleet = get_option('p2p_bp_fleet');
        $this->services = get_option('p2p_bp_services');
        $this->option = get_option('p2p_bp');
	}
    
    //Get Valid Car on DB    
    function availableCar($distance_travel) {
        $car = get_option('p2p_bp_fleet');
        $cars_show = '';
        
        //Get Option
        $distance_price = $this->option['basic']['distance'];
        $system = $this->option['basic']['select_distance'];
        
        //Define basic distances
        $kms = DISTANCE_KM; $miles = DISTANCE_MILE;
        
        $index = 0;
        
        if (!isset($car['body'])) return false;
        
        $totalCar = count($car['body']);
 
        for ($i = 0; $i < $totalCar; $i++) {
            $less_than = $car['lessThan'][$i];
            $more_than = $car['moreThan'][$i];
            if (is_numeric((float)$less_than) && is_numeric((float)$more_than)) {
                $price = ($distance_travel/$$system <= $distance_price)?($distance_travel/$$system) * $less_than:($distance_travel/$$system) * $more_than;
                $cars_show[$index]['price'] = (($price >= $car['minimum'][$i])?round($price,2):$car['minimum'][$i])+get_option('p2p_bp_fleet_increase_ride');
                $cars_show[$index]['id'] = $i;
                $cars_show[$index]['bodytype'] = $car['body'][$i];
                $cars_show[$index]['passenger'] = $car['nPass'][$i];
                $cars_show[$index]['luggage'] = $car['nLugg'][$i];
                $cars_show[$index]['pic'] = $car['pic'][$i];
                $index++;
            }
        }
        return $cars_show;
    }
    
    function getCar($id) {
        $var = array('body', 'lessThan', 'moreThan', 'minimum', 'nPass', 'nLugg', 'color', 'pic');
        $car = array();
        foreach ($var as $v) $car[$v] = $this->fleet[$v][$id];
        return $car;
    }
    
    function selectService($value, $error) {
        
        ($error == 1)?$class_error = 'error_form':$class_error='';
        
        $output = '';
        $total_services = count($this->services['name']);

        if ($total_services != 0) {
            $output .= '<div class="left p2p_reservation_select_label">'.__('Type of Service', P2P_TRANSLATE).': </div>';
            $output .= '<div class="w45p">';
            $output .= '<select class="'.$class_error. ' p2p_reservation_select left" id="servicetype" name="servicetype">';
            $output .= '<option value="0" selected="selected">----- '.__('Select a Service', P2P_TRANSLATE).' -----</option>';
            foreach ($this->services['name'] as $service) {
                ($value == $service)?$selected='selected':$selected='';
                $output .= '<option value="'.$service.'" '.$selected.'>'.ucwords($service).'</option>';            
            }
            
            if (get_option('p2p_bp_services_others')) {
                $output .= '<option value="Other">'.__('Other', P2P_TRANSLATE).'</option>';                        
            }
            $output .= '</select>';
            $output .= '</div> ';
        } else {
            $output .= __('There is no Service Option', P2P_TRANSLATE);
            $output .= '<input type="hidden" name="servicetype" VALUE="">';
        }

        return $output;
    }
}
?>