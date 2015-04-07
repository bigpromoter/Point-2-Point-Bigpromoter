<?php
class P2P_Model_Reservation {
    private $contro_util;
    private $table_name;
    
    function __construct() {
        $this->table_name = "p2p_bp";

        include_once (P2P_DIR_CONTROL . 'util.php');
        $this->control_util = new P2P_Util();
    }
    
    function checkIfAlreadyMade($info) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . $this->table_name;
        $table_prefix = 'p2p_bp_';
            
        $sql = "SELECT p2p_bp_id from {$wpdb->prefix}p2p_bp
                    WHERE   {$table_prefix}first_name = '{$_POST['first_name']}' AND
                        {$table_prefix}last_name = '{$_POST['last_name']}' AND
                        {$table_prefix}p_address = '{$_POST['p_address']}' AND
                        {$table_prefix}p_date = '".$this->control_util->changeDate($_POST['p_date'])."' AND
                        {$table_prefix}p_time = '{$_POST['p_time_h']}:{$_POST['p_time_m']}:00' AND
                        {$table_prefix}d_address = '{$_POST['d_address']}' AND
                        {$table_prefix}ip = '{$_SERVER['REMOTE_ADDR']}'";
                
        $q = $wpdb->get_results($sql);
        
        if (count($q) > 0) return true;
        else return false;
    }
    
    function insertReservation($info, $payment_info) {
    
        global $wpdb;
        
        $table_name = $wpdb->prefix . $this->table_name;
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
                                            $table_prefix.'p_date' => $this->control_util->changeDate($_POST['p_date']),
                                            $table_prefix.'p_time' => $info['p_time_h'].':'.$info['p_time_m'].':00',
                                            $table_prefix.'p_instructions' => $info['p_instructions'],
                                            $table_prefix.'d_address' => $info['d_address'],
                                            $table_prefix.'d_apt' => $info['d_apt'],
                                            $table_prefix.'d_city' => $info['d_city'],
                                            $table_prefix.'d_state' => $info['d_state'],
                                            $table_prefix.'d_zip' => $info['d_zip'],
                                            $table_prefix.'r' => $info['r'],
                                            $table_prefix.'r_p_date' => $this->control_util->changeDate($_POST['r_p_date']),
                                            $table_prefix.'r_p_time' => $info['r_p_time_h'].':'.$info['r_p_time_m'].':00',
                                            $table_prefix.'r_p_instructions' => $info['r_p_instructions'],
                                            $table_prefix.'ip' => $_SERVER['REMOTE_ADDR'],
                                            $table_prefix.'payment_id' => $payment_info['id'],
                                            $table_prefix.'payment_total' => $payment_info['paid'],
                                            $table_prefix.'payment_trip' => $info['trip'],
                                            $table_prefix.'payment_gratuity' => $info['gratuity'],                                            
                                            $table_prefix.'payment_company' => $payment_info['company']
                                ));
        return $q;
    }

    function getReservation($date_from, $date_to, $is_provided) {
        global $wpdb;
        
        $comp =''; //Search

        $comp = 'WHERE '; 
        $from = ($this->control_util->validateDate($this->control_util->changeDate($date_from)))?$this->control_util->changeDate($date_from):'2000-01-01';
        $to = ($this->control_util->validateDate($this->control_util->changeDate($date_to)))?$this->control_util->changeDate($date_to):'2038-12-31';
        
        $comp .= "((p2p_bp_p_date BETWEEN '{$from}' AND '{$to}') OR (p2p_bp_r_p_date BETWEEN '{$from}' AND '{$to}'))";
        
        if ($is_provided != 2) {
            $comp .= " AND ((p2p_bp_done = '{$is_provided}') OR ((p2p_bp_done_r = '{$is_provided}') AND (p2p_bp_r = 1)))";
        }
        
        $order = 'ORDER BY p2p_bp_p_date ASC';
        
        $results = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.$this->table_name.' '.$comp.' '.$order, OBJECT );
                
        return $results;
    }
    
    function deleteReservation($id) {
        
        global $wpdb;
        
        if (isset($id)) {
            $delete = $wpdb->delete($wpdb->prefix.$this->table_name, array('p2p_bp_id' => $id));
            if ($delete) return true;
        }
        return false;
    }
    
    function getReserve($id) {
        global $wpdb;
        return $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.$this->table_name.' WHERE p2p_bp_id = '.$id, OBJECT );    
    }
    
    function isProvided($id, $round) {
        global $wpdb;
        $select = ($round)?'p2p_bp_done_r':'p2p_bp_done';
        $provided = $wpdb->get_results( 'SELECT '.$select.' FROM '.$wpdb->prefix.$this->table_name.' WHERE p2p_bp_id = '.$id, OBJECT );
        return $provided[0]->$select;
    }
    
    function changeProvided($id, $round, $value) {
        global $wpdb;
        $select = ($round)?'p2p_bp_done_r':'p2p_bp_done';
        $update_provide = $wpdb->update($wpdb->prefix.$this->table_name, array($select => $value), array('p2p_bp_id' => $id));
        return $update_provide;
    }
}
?>