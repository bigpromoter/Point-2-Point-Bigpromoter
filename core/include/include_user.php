<?php
    require_once( dirname(__FILE__).'/../../../../../wp-blog-header.php' );
    require_once (dirname(__FILE__).'/../../config.php');
    
    //Call Classes
    include_once (P2P_DIR_CONTROL.'user.php');
    $control_user = new P2P_User();
    
    include_once (P2P_DIR_CONTROL.'form.php');
    $control_form = new P2P_Form();
    
    include_once (P2P_DIR_CONTROL.'shortcode.php');
    $control_shortcode = new P2P_Shortcode();

    include_once (P2P_DIR_CONTROL.'reservation.php');
    $control_reservation = new P2P_Reservation();
    
    include_once (P2P_DIR_CONTROL.'fleet.php');
    $control_fleet = new P2P_Fleet();
    
    include_once (P2P_DIR_CONTROL.'map.php');
    $control_map = new P2P_Map();    
    
    include_once (P2P_DIR_CONTROL.'util.php');
    $control_util = new P2P_Util();
    
    include_once (P2P_DIR_CONTROL.'payment.php');
    $control_payment = new P2P_Payment();
?>