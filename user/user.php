<?php
class User {
    function __construct() {
        //add the [bigpromoter_map] shortcode support
        $this->createShortcode('bigpromoter_map','showMap');
        
        //add the [bigpromoter_reservation] shortcode support
        $this->createShortcode('bigpromoter_reservation','showReservation');
        
    }

    //Declare Shortcodes
    function createShortcode($name, $function) {
        $this->includePage('control','control_user');
        $control = new ControlUser();

        add_shortcode($name, array($control,$function));     
    }
    
	function includePage($folder, $page) { //Include Page on Admin
		include_once (dirname(__FILE__)."/{$folder}/{$page}.php");		
	}    
}
?>