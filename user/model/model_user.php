<?php
class p2p_bp_ModelUser {
    function getCars($order = '') {
    	global $wpdb;
        //require_once( '../../../../../wp-blog-header.php' );
        
        $q = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}p2p_bp_cars {$order}",OBJECT);
        return $q;
    }
    
    function getCar($id) {
    	global $wpdb;
        //require_once( 'wp-blog-header.php' );
        
        $q = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}p2p_bp_cars WHERE p2p_bp_cars_id = {$id}",OBJECT);
        
        return $q[0];        
    }

    function getServices() {
    	global $wpdb;
        //require_once( 'wp-blog-header.php' );
        
        $q = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}p2p_bp_service ORDER BY p2p_bp_service_name ASC",OBJECT);
        if (count($q) > 0) return $q;
        return 0;        
    }
    
    function changeDate($date) {
        $d = explode('/',$date);
        if (count($d) < 3) return false;
        $newD = $d[2].'-'.$d[0].'-'.$d[1];
        return $newD;
    }
    
    function checkIfAlreadyMade($info) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . "p2p_bp";
        $table_prefix = 'p2p_bp_';
            
        $sql = "SELECT p2p_bp_id from {$wpdb->prefix}p2p_bp
                    WHERE   {$table_prefix}first_name = '{$_POST['first_name']}' AND
                        {$table_prefix}last_name = '{$_POST['last_name']}' AND
                        {$table_prefix}p_address = '{$_POST['p_address']}' AND
                        {$table_prefix}p_date = '".$this->changeDate($_POST['p_date'])."' AND
                        {$table_prefix}p_time = '{$_POST['p_time_h']}:{$_POST['p_time_m']}:00' AND
                        {$table_prefix}d_address = '{$_POST['d_address']}' AND
                        {$table_prefix}ip = '{$_SERVER['REMOTE_ADDR']}'";
                
        $q = $wpdb->get_results($sql);
        
        if (count($q) > 0) return true;
        else return false;
    }
    
    function insertReservation($info, $payment_info) {
    
        global $wpdb;
        
        $table_name = $wpdb->prefix . "p2p_bp";
        $table_prefix = 'p2p_bp_';
            

        $q = $wpdb->insert($table_name, array($table_prefix.'first_name' => $info['first_name'],
                                            $table_prefix.'last_name' => $info['last_name'],
                                            $table_prefix.'phone' => $info['phone'],
                                            $table_prefix.'email' => $info['email'],
                                            $table_prefix.'npassenger' => intval($info['npassenger']),
                                            $table_prefix.'nluggage' => intval($info['nluggage']),
                                            $table_prefix.'vehicletype' => $info['vehicletype'],
                                            $table_prefix.'servicetype' => $info['servicetype'],
                                            $table_prefix.'p_address' => $info['p_address'],
                                            $table_prefix.'p_apt' => $info['p_apt'],
                                            $table_prefix.'p_city' => $info['p_city'],
                                            $table_prefix.'p_state' => $info['p_state'],
                                            $table_prefix.'p_zip' => $info['p_zip'],
                                            $table_prefix.'p_date' => $this->changeDate($_POST['p_date']),
                                            $table_prefix.'p_time' => $info['p_time_h'].':'.$info['p_time_m'].':00',
                                            $table_prefix.'p_instructions' => $info['p_instructions'],
                                            $table_prefix.'d_address' => $info['d_address'],
                                            $table_prefix.'d_apt' => $info['d_apt'],
                                            $table_prefix.'d_city' => $info['d_city'],
                                            $table_prefix.'d_state' => $info['d_state'],
                                            $table_prefix.'d_zip' => $info['d_zip'],
                                            $table_prefix.'r' => $info['r'],
                                            $table_prefix.'r_p_date' => $this->changeDate($_POST['r_p_date']),
                                            $table_prefix.'r_p_time' => $info['r_p_time_h'].':'.$info['r_p_time_m'].':00',
                                            $table_prefix.'r_p_instructions' => $info['r_p_instructions'],
                                            $table_prefix.'ip' => $_SERVER['REMOTE_ADDR'],
                                            $table_prefix.'payment_id' => $payment_info['id'],
                                            $table_prefix.'payment_value' => $payment_info['paid'],
                                            $table_prefix.'payment_company' => $payment_info['company']
                                ));
        return $q;
    }
}
?>