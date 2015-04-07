<?php
    require_once (dirname(__FILE__).'/../../config.php');
    
    global $wpdb;
    if (!defined('P2P_TABLE_PREFIX')) define('P2P_TABLE_PREFIX', $wpdb->prefix.'p2p_bp_');
    
    //Call Classes
    include_once (P2P_DIR_CONTROL.'form.php');
    $control_form = new P2P_Form();

    //Call Classes
    include_once (P2P_DIR_CONTROL.'map.php');
    $control_map = new P2P_Map();
    include_once (P2P_DIR_CONTROL.'util.php');
    $control_util = new P2P_Util();
    include_once (P2P_DIR_MODEL.'reservation.php');
    $model_reservation = new P2P_Model_Reservation();
?>