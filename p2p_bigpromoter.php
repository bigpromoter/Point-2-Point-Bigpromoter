<?php //Info
/**
 * @package P2P BigPromoter
 * @version New Alpha 1.1.0
 */
/*
Plugin Name: P2P BigPromoter
Plugin URI: http://wordpress.org/plugins/P2P_Big_Promoter/
Description: Plugin to calculate fare for transportation company.
Author: Big Promoter
Version: New Alpha 1.1.0
Author URI: http://bigpromoter.com/
License: GPL2
*/

//Avoid Plugin to be called separeted then WP
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

//Check if Absolute Path is defined
if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	die();
}

//Define Commoms
define('P2P_DIR_IMAGES',        plugins_url('assets/images/', __FILE__));
define('P2P_DIR_CSS',           plugins_url('assets/style/', __FILE__));
define('P2P_DIR_JS',            plugins_url('assets/script/', __FILE__));
define('P2P_AJAX_USER',   plugins_url('core/view/user/', __FILE__));
include_once (dirname(__FILE__).'/config.php');

function p2p_bp_styles() {
    $option = get_option('p2p_bp');
    
    //Register Style Sheet
    wp_register_style('bp_basic_style', P2P_DIR_CSS."style.css");
    wp_register_style('bp_custom_style', P2P_DIR_CSS."custom-style.css");
    wp_register_style('bp_responsive', P2P_DIR_CSS."responsive.css");
    wp_register_style('bp_bigpromoter_switch', P2P_DIR_CSS."bigpromoter-switch.css");
                    
    //Enqueue Registered Style Sheet
    wp_enqueue_style('bp_basic_style');
    wp_enqueue_style('bp_custom_style');
    wp_enqueue_style('bp_responsive');
    wp_enqueue_style('bp_bigpromoter_switch');
    
    //Check if user want to load jQuery UI
    if (!$option['conflict']['jquery_ui']) {
        wp_register_style('bp_jquery_ui', P2P_DIR_CSS."jquery-ui.min.css");
        wp_enqueue_style('bp_jquery_ui');
    }

    //Check if user want to load Font Awesome
    if (!$option['conflict']['font_awesome']) {
        wp_register_style('bp_font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        wp_enqueue_style('bp_font_awesome');
    }

    //Check if user want to load Color Picker
    if (is_admin() && !$option['conflict']['wp_color_picker']) {
        wp_enqueue_style('wp-color-picker');
    }
}

function p2p_bp_scripts() {
    $option = get_option('p2p_bp');
    unset($option['email']);
    unset($option['payment']);
    unset($option['calendar']);
    unset($option['color']);
    
    $translate = array('must_have_more_than' => __('must have more than',P2P_TRANSLATE),
                       'characters' => __('characters',P2P_TRANSLATE),
                       'must_be_a_number' => __('must be a number',P2P_TRANSLATE),
                       'choose_image' => __('Choose Image',P2P_TRANSLATE),
                       'service' => __('Service',P2P_TRANSLATE),
                       'body' => __('Body',P2P_TRANSLATE),
                       'less_than' => __('Less Than',P2P_TRANSLATE),
                       'more_than' => __('More Than',P2P_TRANSLATE),
                       'minimum' => __('Minimum',P2P_TRANSLATE),
                       'passenger' => __('Passenger',P2P_TRANSLATE),
                       'luggage' => __('Luggage',P2P_TRANSLATE),
                       'color' => __('Color',P2P_TRANSLATE),
                       'upload_image' => __('Upload Image',P2P_TRANSLATE),
                       'confirm' => __('Confirm',P2P_TRANSLATE),
                       'close' => __('Close',P2P_TRANSLATE),
                       'car_deleted' => __('Car deleted',P2P_TRANSLATE),
                       'enabled' => __('Enabled',P2P_TRANSLATE),
                       'disabled' => __('Disabled',P2P_TRANSLATE),
                       'yes' => __('Yes',P2P_TRANSLATE),
                       'no' => __('No',P2P_TRANSLATE),
                       'sandbox' => __('Sandbox',P2P_TRANSLATE),
                       'live' => __('Live',P2P_TRANSLATE),
                       'production' => __('Production',P2P_TRANSLATE),
                       'confirm_deletion' => __('Please, confirm the deletion of the',P2P_TRANSLATE),
                       'car' => __('Car',P2P_TRANSLATE),
                       'deleted' => __('deleted',P2P_TRANSLATE),
                       'reservation' => __('reservation',P2P_TRANSLATE)
                       );
    
    //Check if user want to load jQuery UI DatePicker and jQuery UI Button
    //Build Array
    $load_script = array();
    array_push($load_script,'jquery-core');
    if (!$option['conflict']['jquery_ui_datepicker']) array_push($load_script,'jquery-ui-datepicker');
    if (!$option['conflict']['jquery_ui_button']) array_push($load_script,'jquery-ui-button');
    wp_register_script('bp_basic_script', P2P_DIR_JS."script.js",$load_script);
    wp_enqueue_script('bp_basic_script');
    wp_localize_script('bp_basic_script', 'p2p_script', array(
                                                        'p2p_bp_nonce' => wp_create_nonce('p2p_bp_nonce'),
                                                        'p2p_bp' => $option,
                                                        'p2p_bp_fleet_price' => P2P_AJAX_USER.'fleet_price.php',
                                                        'p2p_bp_api_token' => get_option('p2p_bp_api_token'),
                                                        'p2p_bp_api_site' => get_option('p2p_bp_api_site'),
                                                        'p2p_bp_translate' => $translate,
                                                        'p2p_bp_max_passenger' => P2P_MAX_PASSENGER,
                                                        'p2p_bp_max_luggage' => P2P_MAX_LUGGAGE
                                                        ));
        
    //Check if user want to load jQuery from Google Server
    if($option['conflict']['load_jquery_google'] == 1) {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', false, '2.1.3');
    }
    
    wp_register_script( 'p2p_bigpromoter_switch_script',P2P_DIR_JS.'bigpromoter-switch.js');
    wp_enqueue_script('p2p_bigpromoter_switch_script');
    
    //Not in Admin
    if (!is_admin()) {
        wp_register_script( 'p2p_user_script',P2P_DIR_JS.'user.js');
        wp_enqueue_script('p2p_user_script');
    }
    //Only in Admin
    if (is_admin()) {
        wp_register_script( 'p2p_admin_script',P2P_DIR_JS.'admin.js');
        wp_enqueue_script('p2p_admin_script');
        
        //Check if user want to load Color Picker
        if (!$option['conflict']['wp_color_picker'])
            wp_enqueue_script('color', P2P_DIR_JS.'color.js', array( 'wp-color-picker' ), false, true);
    }
}

//Includes
if (is_admin()) {
    //Add Scripts and Styles to Admin View
    add_action ('admin_enqueue_scripts', 'p2p_bp_styles');
    add_action ('admin_enqueue_scripts', 'p2p_bp_scripts');

	// Call Admin Functions and Screens
	include_once (P2P_DIR_CONTROL.'admin.php');
    $p2p_admin = new P2P_Admin();
    
    //Activate and Deactivate Plugin (Create and Delete Table)
    register_activation_hook(__FILE__, array($p2p_admin,'p2p_bp_activate'));
    register_deactivation_hook(__FILE__, array($p2p_admin,'p2p_bp_deactivate'));
    
} else {
    //Add Scripts and Styles to User View
    add_action ('wp_enqueue_scripts', 'p2p_bp_styles');
    add_action ('wp_enqueue_scripts', 'p2p_bp_scripts');

	// Call User Functions and Screens
	include_once (P2P_DIR_CONTROL.'user.php');
    $p2p_user = new P2P_User();
}

//Call Translation
add_action( 'init', 'plugin_translate'); 
function plugin_translate() {
    $mo_file = P2P_DIR . 'language/'. get_locale() . '.mo'; 
    load_textdomain( P2P_TRANSLATE, $mo_file ); 
    load_plugin_textdomain( P2P_TRANSLATE, false, $mo_file = P2P_DIR . 'language/'); 
}

?>