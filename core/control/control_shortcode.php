<?php
class P2P_Shortcode {
    private $option;
    function __construct() {
        //add shortcode support
        $this->createShortcode('p2p_bp_map','showMap');
        $this->createShortcode('p2p_bp_reservation','showReservation');
        
        $this->option = get_option('p2p_bp');
    }

    //Declare Shortcodes
    function createShortcode($name, $function) {
        add_shortcode($name, array($this, $function));     
    }
    
    function showMap() {
        include_once(P2P_DIR_CONTROL.'util.php');
        $control_util = new P2P_Util();
        
        //Call View
        $html = file_get_contents(P2P_DIR_VIEW_USER.'map.php');
        $html = str_replace('%customcolor%', $control_util->color(), $html); //Insert Custom Color
        $html = str_replace('%width%', $this->option['map']['width'], $html);
        $html = str_replace('%height%', $this->option['map']['height'], $html);
        $html = str_replace('%start_map_lat%',$this->option['map']['start_lat'], $html);
        $html = str_replace('%start_map_lon%', $this->option['map']['start_lon'], $html);
        $html = str_replace('%start_map_zoom%', $this->option['map']['zoom'], $html);
        $html = str_replace('%google_api%', $this->option['map']['google_api'], $html);
        $html = str_replace('%plugin_address%', P2P_DIR_VIEW_USER, $html);
        
        return $html;
    }
    
    function showReservation() {
        include_once (P2P_DIR_VIEW_USER.'reservation.php');
    }
}
?>