<?php
class P2P_Admin {
	function __construct() {
		// Add the admin options page
		add_action('admin_menu', array($this,'addPage'));
	
		//Call register settings function
		add_action('admin_init', array($this,'initSettings'));
        
        add_action('admin_init', array($this, 'p2p_bp_button')); //Insert Button on Page/Post
        
        //Call Ajax Functions
        $this->callAjax();
    }
	
	function addPage() {
		//Add Admin Menus
		add_menu_page( 'Point 2 Point', 'Point 2 Point - Big Promoter', 'manage_options', 'p2p_bigpromoter', array($this,'setting'), P2P_DIR_IMAGES.'icon.png');
        add_submenu_page('p2p_bigpromoter',__('Settings', P2P_TRANSLATE),__('Settings', P2P_TRANSLATE),'manage_options','p2p_bigpromoter',array($this,'setting'));
		add_submenu_page('p2p_bigpromoter',__('Manage Fleet', P2P_TRANSLATE),__('Manage Fleet', P2P_TRANSLATE),'manage_options','p2p_bigpromoter_fleet',array($this,'manageFleet'));
		add_submenu_page('p2p_bigpromoter',__('Manage Services', P2P_TRANSLATE),__('Manage Services', P2P_TRANSLATE),'manage_options','p2p_bigpromoter_services',array($this,'manageServices'));		
		add_submenu_page('p2p_bigpromoter',__('Manage Reservation', P2P_TRANSLATE),__('Manage Reservation', P2P_TRANSLATE),'manage_options','p2p_bigpromoter_reservation',array($this,'manageReservation'));
		add_submenu_page('p2p_bigpromoter',__('Export/Import', P2P_TRANSLATE),__('Export/Import', P2P_TRANSLATE),'manage_options','p2p_bigpromoter_export_import',array($this,'exportImport'));
    }
    
	function registerSettings($settings, $section) {
		foreach($settings as $set) {
			register_setting($section[0], $set);
		}
		add_settings_section(
			$section[0],	
			$section[1],	
			$section[2],	
			$section[3]	
		);
	}
	
	function initSettings() {
		//Register BASIC Settings
		$settings = array(
                        //Settings'
                        'p2p_bp',
                        
                        //Api
                        'p2p_bp_api_token',
                        'p2p_bp_api_site',
                        
                        //Save Last Tab Saved
                        'p2p_bp_last_tab'
                        
                        
                        );
		$section_general = array('p2p_bigpromoter_main',
				      'Main Page',
				      'p2p_bigpromoter_main_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings, $section_general);		
		
		//register SERVICE Settings
		$settings_services = array('p2p_bp_services', 'p2p_bp_services_others');
		$section_services = array('p2p_bigpromoter_services',
				      'Service Settings',
				      'p2p_bigpromoter_service_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_services, $section_services);	
    		
		//register FLEET Settings
		$settings_fleet = array('p2p_bp_fleet', 'p2p_bp_fleet_increase_ride');
		$section_fleet = array('p2p_bigpromoter_fleet',
				      'Fleet Settings',
				      'p2p_bigpromoter_fleet_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_fleet,$section_fleet);	
    
    }
	
	function includePage($page) {//Include Page on Admin
		include_once (P2P_DIR_VIEW_ADMIN."{$page}.php");		
	}
	
	function setting() {//See includePage
		$this->includePage('setting');
	}	

	function manageFleet() {//See includePage
		$this->includePage('manage_fleet');
	}

	function manageServices() {//See includePage
		$this->includePage('manage_services');
	}

	function manageReservation() {//See includePage
		$this->includePage('manage_reservation');
	}
    
	function exportImport() {//See includePage
		$this->includePage('export_import');
	}
    
    //What happens when the plugin is activated	
	function p2p_bp_activate() {
		
		//Create Tables
		include_once ( P2P_DIR_MODEL.'activate.php');
		$model = new P2P_Model_Activate();	
		$model->activateCreateMainTable();

        
        add_option('p2p_bp_last_tab', 'p2p_bp_basic');
        
		//Insert Options
        $initial_option = array('basic' => array('reservation_page' => '', 'select_distance' => 'miles', 'distance' => '50', 'select_currency'=> '$', 'placeholder' => 1, 'insert_gratuity' => 0),
                        'extra' => array('enabled' => 0, 'car_seat' => 0, 'car_seat_value' => 0),
                        'conflict' => array('jquery_ui_datepicker' => 0, 'jquery_ui_button'=> 0, 'font_awesome' => 0, 'jquery_ui' => 0, 'wp_color_picker' => 0, 'load_jquery_google' => 0),
                        'map' => array('google_api' => '', 'start_lat' => '37.7749295', 'start_lon' => '-122.4194155', 'address' => 'San Francisco/CA', 'zoom' => '16', 'width'=> '100%', 'height' => '400px'),
                        'color'=> array('active' => '0','custom_css' => '', 'label_color' => '', 'label_background' => '', 'label_border' => 0, 'label_border_color' => '', 'input_color' => '', 'input_background' => '', 'input_border' => 0, 'input_border_color' => '', 'button_color' => '', 'button_background' => '', 'button_border' => 0, 'button_border_color' => ''
),
                        'calendar' => array('enabled' => '0','title' => '%first_name% %last_name% - %email%','description_client' => __('Client Information:
Name: %first_name% %last_name%
Phone: %phone%
E-mail: %email%
Passenger: %npassenger%
Luggage: %nluggage%
Vehicle: %vehicletype%
Service: %servicetype%

Amount Paid: %amount_paid%
Transaction Id: %transaction_id%',P2P_TRANSLATE),'description_location' => __('When: %p_date% at %p_time_h%:%p_time_m%
Instructions: %p_instructions%

Pick Up Information:
Address: %p_address% #%p_apt%
City: %p_city%
Zip Code: %p_zip%

Drop Off Information:
Address: %d_address% #%d_apt%
City: %d_city%
Zip Code: %d_zip%',P2P_TRANSLATE), 'keyfilename' => '', 'serviceemail' => '', 'id'=> ''),
                        'payment' => array('type'=> 'no', 'nopayment_creditcard' => '0','paypal_enviroment' => 'sandbox','paypal_client_ID' => '','paypal_secret' => '','braintree_enviroment' => 'sandbox','braintree_merchantId' => '','braintree_publicKey' => '','braintree_privateKey' => '','braintree_config_code' => ''
),
                        'advanced' => array('delete_table' => 0, 'delete_settings' => 0),
                        'email' => array('name' => '', 'address' => '', 'pass' => '', 'smtpsecure' => 'ssl', 'host' => '', 'port' => '', 'curl' => 0, 'admin' => 0, 'debug' => 0, 'signature' => '', 'color_bg' => '', 'color_txt' => '', 'receive_1' => '', 'receive_name_1' => '', 'receive_2' => '', 'receive_name_2' => '', 'receive_3' => '', 'receive_name_3' => '')
                  );
        
        add_option('p2p_bp', $initial_option);
	}
	
	//What happens when the plugin is deactivated
    function p2p_bp_deactivate() {
		include_once (P2P_DIR_MODEL.'activate.php');
		$model = new P2P_Model_Activate();
        
        $option = get_option('p2p_bp');

        if($option['advanced']['delete_table']) { //Drop Table
            $model->deactivateDropMainTable();
        }

        if($option['advanced']['delete_settings']) { //Remove Options
            $model->deactivateRemoveOptions();
        }
    }
    
    //Call Ajax Functions
    function callAjax() {
        include_once (P2P_DIR_CONTROL.'ajax.php');
        $p2p_ajax = new P2P_Ajax();
    }
    
    //Add Button on Edit/Add Post/Page
    function p2p_bp_register_button( $buttons ) {
       array_push( $buttons, "|", "p2p_bp_map", "p2p_bp_reservation" );
       return $buttons;
    }
    
    function add_p2p_bp_plugin( $plugin_array ) {
       $plugin_array['p2p_bp'] = P2P_DIR_JS . 'plugin.js';
       return $plugin_array;
    }
    
    function p2p_bp_button() {
       if ((!current_user_can('edit_posts')) && (!current_user_can('edit_pages'))) return;
    
       if (get_user_option('rich_editing') == 'true') {
          add_filter('mce_external_plugins', array($this,'add_p2p_bp_plugin'));
          add_filter('mce_buttons', array($this,'p2p_bp_register_button'));
       }
    }

}
?>
