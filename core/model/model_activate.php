<?php
class P2P_Model_Activate {
    
    private $table_name;
    function __construct() {
        $this->table_name = "p2p_bp";
    }

/*****************************************************/
//  ACTIVATE - CREATE TABLES
/*****************************************************/
    function activateCreateMainTable () {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table_name = $wpdb->prefix . $this->table_name;
		$sql_main = "CREATE TABLE IF NOT EXISTS {$table_name} (p2p_bp_id INT NOT NULL AUTO_INCREMENT,
										p2p_bp_first_name VARCHAR(30) NOT NULL,
										p2p_bp_last_name VARCHAR(30) NOT NULL,
										p2p_bp_phone VARCHAR(20) NOT NULL,
										p2p_bp_email VARCHAR(40) NOT NULL,
										p2p_bp_npassenger INT NOT NULL,
										p2p_bp_nluggage INT NOT NULL,
										p2p_bp_vehicletype VARCHAR(40) NOT NULL,
										p2p_bp_servicetype VARCHAR(40) NOT NULL,
										p2p_bp_p_address VARCHAR(60) NOT NULL,
										p2p_bp_p_apt VARCHAR(10) NOT NULL,
										p2p_bp_p_city VARCHAR(20) NOT NULL,
										p2p_bp_p_state VARCHAR(20) NOT NULL,
										p2p_bp_p_zip INT NOT NULL,
										p2p_bp_p_date DATE NOT NULL,
										p2p_bp_p_time TIME NOT NULL,
										p2p_bp_p_instructions TEXT NOT NULL,
										p2p_bp_d_address VARCHAR(60) NOT NULL,
										p2p_bp_d_apt VARCHAR(10) NOT NULL,
										p2p_bp_d_city VARCHAR(20) NOT NULL,
										p2p_bp_d_state VARCHAR(20) NOT NULL,
										p2p_bp_d_zip INT NOT NULL,
										p2p_bp_r BOOL NOT NULL,
										p2p_bp_r_p_date DATE NOT NULL,
										p2p_bp_r_p_time TIME NOT NULL,
										p2p_bp_r_p_instructions TEXT NOT NULL,
										p2p_bp_ip VARCHAR(45) NOT NULL,
                                        p2p_bp_payment_id VARCHAR(60) NOT NULL,
                                        p2p_bp_payment_total FLOAT NOT NULL,
                                        p2p_bp_payment_trip FLOAT NOT NULL,
                                        p2p_bp_payment_gratuity FLOAT NOT NULL,
                                        p2p_bp_payment_company VARCHAR(60) NOT NULL,
                                        p2p_bp_done BOOL NOT NULL,
                                        p2p_bp_done_r BOOL NOT NULL,
										PRIMARY KEY(p2p_bp_id));";
		dbDelta($sql_main);
    }
    
    function deactivateDropMainTable() {
    	global $wpdb;
        $table_name = $wpdb->prefix . $this->table_name;
        $wpdb->query("DROP TABLE {$table_name}");
    }

    function deactivateRemoveOptions() {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'p2p_bp%'");
    }
    
/******************************************/
//  END ACTIVATE - CREATE TABLES
/******************************************/


}
?>