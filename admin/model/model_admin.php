<?php
class ModelAdmin {

    public $reservation = array();
    public $fleet = array();
    public $services  = array();
    
    function __construct() {
        global $wpdb;
        
        //Define table name and table prefix
        $this->reservation = array("table" => $wpdb->prefix . "p2p_bp",
                            "prefix"=> "p2p_bp_"
                            );
        //$reservation['table'] => table name => wp_p2p_bp
        //$reservation['prefix'] => table prefix => p2p_bp_
        
        //Define table name and table prefix (See $reservation)        
        $this->fleet = array("table" => $wpdb->prefix . "p2p_bp_cars",
                        "prefix"=> "p2p_bp_cars_"
                        );

        //Define table name and table prefix (See $reservation)        
        $this->service = array("table" => $wpdb->prefix . "p2p_bp_service",
                        "prefix"=> "p2p_bp_service_"
                        );
    }

    function getResults($table,$comp = '') {
        global $wpdb;
        
        $results = $wpdb->get_results( 'SELECT * FROM '.$table.' '.$comp, OBJECT );
                
        return $results;
    }
    
    function removeReservation($id) {
        
        global $wpdb;
        
        if (isset($id)) {
            $delete = $wpdb->delete($this->reservation['table'], array($this->reservation['prefix'].'id' => $id));
            if ($delete) return true;
        }
        return false;
    }
    
    function doneReservation($id, $trip, $value) {
        
        global $wpdb;
        
        $round = ($trip == 1)?"_r":"";        
        $table = $this->reservation['table'];
        $prefix = $this->reservation['prefix'];
        
        $q = $wpdb->query("
						UPDATE {$table}
						SET
							{$prefix}done{$round}='".$value."'
							
						WHERE {$prefix}id='".$id."'
			");
        if($q) return true;
        return false;
    }

/*****************************************************/
// FLEET
/*****************************************************/
    
    function insertCar($table, $prefix, $info) {
        global $wpdb;
        
        $q = $wpdb->query("
                    INSERT INTO {$table} (
                                    {$prefix}id,
                                    {$prefix}enabled,
                                    {$prefix}name,
                                    {$prefix}passenger,
                                    {$prefix}luggage,
                                    {$prefix}pic,
                                    {$prefix}value_lower,
                                    {$prefix}value_higher,
                                    {$prefix}color,
                                    {$prefix}min
                                    ) 
                    VALUES (
                        NULL,
                        '".$info['active']."',
                        '".$info['body']."',
                        '".$info['passenger']."',
                        '".$info['luggage']."',
                        '".$info['image']."',
                        '".$info['less_than']."',
                        '".$info['more_than']."',
                        '".$info['color']."',
                        '".$info['min']."'
                    )
            ");
        
        return $q;
    }
    
    function editCar($table, $prefix, $info) {
        global $wpdb;
        
        $q = $wpdb->query("
					       
						UPDATE {$table}
						SET
							{$prefix}enabled='".$info['active']."',
							{$prefix}name='".$info['body']."',
							{$prefix}passenger='".$info['passenger']."',
							{$prefix}luggage='".$info['luggage']."',
							{$prefix}pic='".$info['image']."',
							{$prefix}value_lower='".$info['less_than']."',
							{$prefix}value_higher='".$info['more_than']."',
							{$prefix}color='".$info['color']."',
                            {$prefix}min='".$info['min']."'
							
						WHERE {$prefix}id='".$info['id']."'
			");
        return $q;
    }
    
    function deleteCar($table, $prefix, $id) {
        global $wpdb;
    
        $q = $wpdb->query("
                        DELETE FROM {$table}							
                        WHERE {$prefix}id='".$id."'
            ");
        
        return $q;
    }
/*****************************************************/
// SERVICES
/*****************************************************/
    function insertService($table, $prefix, $info) {
        global $wpdb;
        
        $q = $wpdb->query("
                    INSERT INTO {$table} (
                                    {$prefix}id,
                                    {$prefix}name
                                    ) 
                    VALUES (
                        NULL,
                        '".$info['service']."'
                    )
            ");
        
        return $q;
    }

    function editService($table, $prefix, $info) {
        global $wpdb;
        
        $q = $wpdb->query("
					       
						UPDATE {$table}
						SET
							{$prefix}name='".$info['service']."'
							
						WHERE {$prefix}id='".$info['id']."'
			");
        return $q;
    }
    
    function deleteService($table, $prefix, $id) {
        global $wpdb;
    
        $q = $wpdb->query("
                        DELETE FROM {$table}							
                        WHERE {$prefix}id='".$id."'
            ");
        
        return $q;
    }
/*****************************************************/
//  ACTIVATE - CREATE TABLES
/*****************************************************/
    function activateCreateMainTable () {
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table_name = $wpdb->prefix . "p2p_bp";
	
		$sql_main = "CREATE TABLE {$table_name} (p2p_bp_id INT NOT NULL AUTO_INCREMENT,
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
                                        p2p_bp_payment_value FLOAT NOT NULL,
                                        p2p_bp_payment_company VARCHAR(60) NOT NULL,
                                        p2p_bp_done BOOL NOT NULL,
                                        p2p_bp_done_r BOOL NOT NULL,
										PRIMARY KEY(p2p_bp_id));";
		dbDelta( $sql_main );
    }
    
    function activateCreateCarsTable () {
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table_name = $wpdb->prefix . "p2p_bp";
        
		$sql_cars = "CREATE TABLE {$table_name}_cars (p2p_bp_cars_id INT NOT NULL AUTO_INCREMENT,
														p2p_bp_cars_name VARCHAR(60) NOT NULL,
														p2p_bp_cars_passenger INT NOT NULL,
														p2p_bp_cars_luggage INT NOT NULL,
														p2p_bp_cars_pic VARCHAR(100) NOT NULL,
														p2p_bp_cars_value_lower FLOAT NOT NULL,
														p2p_bp_cars_value_higher FLOAT NOT NULL,
														p2p_bp_cars_enabled BOOL NOT NULL,
														p2p_bp_cars_color INT NOT NULL,
                                                        p2p_bp_cars_min INT NOT NULL,
														PRIMARY KEY(p2p_bp_cars_id));";
		dbDelta( $sql_cars );
    }
    
    function activateCreateServicesTable () {
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table_name = $wpdb->prefix . "p2p_bp";
       
		$sql_service = "CREATE TABLE {$table_name}_service (p2p_bp_service_id INT NOT NULL AUTO_INCREMENT,
															p2p_bp_service_name VARCHAR(60) NOT NULL,
															PRIMARY KEY(p2p_bp_service_id));";
		dbDelta( $sql_service );    
    }

/******************************************/
//  END ACTIVATE - CREATE TABLES
/******************************************/


}
?>