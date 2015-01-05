<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

    global $wpdb;

    $table_name = $wpdb->prefix . "p2p_bp";    
    
    //Remove table p2p_bp
    $wpdb->query( "DROP TABLE {$table_name}");

    //Remove table p2p_bp_cars
    $wpdb->query( "DROP TABLE {$table_name}_cars");

    //Remove table p2p_bp_services
    $wpdb->query( "DROP TABLE {$table_name}_services");
?>