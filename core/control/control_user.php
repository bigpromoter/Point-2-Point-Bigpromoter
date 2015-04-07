<?php
class P2P_User {
    
    private $option;
	function __construct() {
        //Call Shortcode Functions
        $this->callShortcode();
        
        //Call Ajax Functions
        $this->callAjax();
        
        $this->option = get_option('p2p_bp');
	}
    
    //Call Ajax Functions
    function callAjax() {
        include_once (P2P_DIR_CONTROL.'ajax.php');
        $control_ajax = new P2P_AJAX();
    }
    
    //Call Shortcode Functions
    function callShortcode() {
        include_once (P2P_DIR_CONTROL.'shortcode.php');
        $control_shortcode = new P2P_Shortcode();
    }
}
?>