<?php //Info
/**
 * @package P2P BigPromoter
 * @version Alpha 1.0.9
 */
/*
Plugin Name: P2P BigPromoter
Plugin URI: http://wordpress.org/plugins/P2P_Big_Promoter/
Description: Plugin to calculate fare for transportation company.
Author: Big Promoter
Version: Alpha 1.0.9
Author URI: http://bigpromoter.com/
License: GPL2
*/
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	die();
}


function bp_styles() {
    //Register Style Sheet
    wp_register_style('bp_basic_style', plugins_url("system/style/style.css",__FILE__));
    wp_register_style('bp_custom_style', plugins_url("system/style/custom-style.css",__FILE__));
    wp_register_style('bp_responsive', plugins_url("system/style/responsive.css",__FILE__));
                    
    //Enqueue Registered Style Sheet
    wp_enqueue_style('bp_basic_style');
    wp_enqueue_style('bp_custom_style');
    wp_enqueue_style('bp_responsive');
    
    if (!get_option('p2p_conflict_jquery_ui')) {
        wp_register_style('bp_jquery_ui', plugins_url("system/style/jquery-ui.min.css",__FILE__));
        wp_enqueue_style('bp_jquery_ui');
    }

    if (!get_option('p2p_conflict_font_awesome')) {
        wp_register_style('bp_font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
        wp_enqueue_style('bp_font_awesome');
    }
    
    if (!get_option('p2p_conflict_bootstrap_css')) {
        wp_register_style('bp_bootstrap_css', plugins_url("system/style/custom-bootstrap.css",__FILE__));
        wp_enqueue_style('bp_bootstrap_css');
    }

    if (is_admin() && !get_option('p2p_conflict_wp_color_picker')) {
        wp_enqueue_style('wp-color-picker');
    }
}

function bp_scripts() {

    //Build Array
    $load_script = array();
    array_push($load_script,'jquery-core');
    if (!get_option('p2p_conflict_jquery_ui_datepicker')) array_push($load_script,'jquery-ui-datepicker');
    if (!get_option('p2p_conflict_jquery_ui_button')) array_push($load_script,'jquery-ui-button');

    wp_register_script( 'bp_basic_script', plugins_url("system/script/script.js",__FILE__),$load_script);
    wp_enqueue_script('bp_basic_script');
    
    if (!get_option('p2p_conflict_bootstrap_js')) {
        wp_register_script( 'bp_bootstrap_js', plugins_url("system/script/bootstrap.min.js",__FILE__));
        wp_enqueue_script('bp_bootstrap_js');
    }
    
    if(get_option('p2p_conflict_load_jquery_google')) {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', false, '2.1.3');
    }
    
    //Only in Admin
    if (is_admin() && !get_option('p2p_conflict_wp_color_picker')) {
        wp_enqueue_script('color', plugins_url( 'system/script/color.js', __FILE__ ), array( 'wp-color-picker' ), false, true);
    }
}

//Add Scripts and Styles to User View
add_action ('wp_enqueue_scripts', 'bp_styles');
add_action ('wp_enqueue_scripts', 'bp_scripts');

//Add Scripts and Styles to Admin View
add_action ('admin_enqueue_scripts', 'bp_styles');
add_action ('admin_enqueue_scripts', 'bp_scripts');

//Includes
if (is_admin()) {
	/* Call Admin Functions and Screens */
	include_once (dirname(__FILE__)."/admin/admin.php");
    $admin = new p2p_bp_Admin();
    
//System
//Activate and Deactivate Plugin
    register_activation_hook(__FILE__, array($admin,'p2p_activate'));
    register_deactivation_hook(__FILE__, array($admin,'p2p_deactivate'));    
} else {
	/* Call User Functions and Screens */
	include_once (dirname(__FILE__)."/user/user.php");
    $user = new p2p_bp_User();
}


?>