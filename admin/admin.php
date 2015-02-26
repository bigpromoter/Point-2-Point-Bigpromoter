<?php
class p2p_bp_Admin {
	function __construct() {
		// Add the admin options page
		add_action('admin_menu', array($this,'addPage'));
	
		//Call register settings function
		add_action( 'admin_init', array($this,'initSettings'));
	}
	
	function addPage() {
		//Add Admin pages
		add_menu_page( 'Point 2 Point', 'Point 2 Point - Big Promoter', 'manage_options', 'p2p_bigpromoter', array($this,'bpSetting'), plugins_url().'/p2p_bigpromoter/system/images/icon.png');
		add_submenu_page('p2p_bigpromoter','Manage Fleet','Manage Fleet','manage_options','p2p_bigpromoter_cars',array($this,'bpManageFleet'));
		add_submenu_page('p2p_bigpromoter','Manage Services','Manage Services','manage_options','p2p_bigpromoter_services',array($this,'bpManageServices'));		
		add_submenu_page('p2p_bigpromoter','Manage Reservation','Manage Reservation','manage_options','p2p_bigpromoter_reservation',array($this,'bpManageReservation'));		
	}
	
	function registerSettings($settings, $section) {
		foreach($settings as $set) {
			register_setting($section[0],$set);
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
		$settings_basic = array('reservation_page',
				        'select_distance',
				        'distance',
				        'select_currency',
				        'placeholder',
                        'insert_gratuity',
                        'extra_requirement',
                        'extra_car_seat',
                        'extra_car_seat_value'
                        
                        );
		$section_map = array('p2p_bigpromoter_main',
				      'Main Page',
				      'p2p_bigpromoter_main_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_basic,$section_map);	
		
		//register MAP Settings
		$settings_map = array('google_api',
                        'start_map_lon',
				       'start_map_lat',
				       'start_map_address',
				       'start_map_zoom',
				       'map_width',
				       'map_height');
		$section_map = array('p2p_bigpromoter_options',
				      'Map Settings',
				      'p2p_bigpromoter_options_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_map,$section_map);

		//register color Settings
		$settings_map = array('p2p_color',
								'p2p_label_color',
							   'p2p_label_background',
							   'p2p_label_border',
							   'p2p_label_border_color',
							   'p2p_input_color',
							   'p2p_input_background',
							   'p2p_input_border',
							   'p2p_input_border_color',
							   'p2p_button_color',
							   'p2p_button_background',
							   'p2p_button_border',
							   'p2p_button_border_color',
                               'p2p_custom_css');
		$section_map = array('p2p_bigpromoter_color',
				      'Color Settings',
				      'p2p_bigpromoter_color_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_map,$section_map);

				//register CALENDAR Settings
        $settings_calendar = array('p2p_calendar_enabled',
                                        'p2p_calendar_keyfilename',
										'p2p_calendar_serviceemail',
										'p2p_calendar_used',
										'p2p_calendar_timezone',
										'p2p_calendar_title',
										'p2p_calendar_description_client',
										'p2p_calendar_description_location'
		);
		$section_calendar = array('p2p_bigpromoter_calendar',
								'Calendar Settings',
								'p2p_bigpromoter_calendar_section_text',
								'p2p_bigpromoter');
		$this->registerSettings($settings_calendar,$section_calendar);

		//register CALENDAR Settings
		$settings_bigpromoter = array('p2p_bigpromoter_api',
										'p2p_bigpromoter_site'
		);
		$section_bigpromoter = array('p2p_bigpromoter_api',
								'BigPromoter Settings',
								'p2p_bigpromoter_bigpromoter_section_text',
								'p2p_bigpromoter');
		$this->registerSettings($settings_bigpromoter,$section_bigpromoter);
		
		//register PAYMENT Settings
		$settings_payment = array('p2p_payment_type',
                                        'p2p_braintree_enviroment',
										'p2p_braintree_merchantId',
										'p2p_braintree_publicKey',
										'p2p_braintree_privateKey',
										'p2p_braintree_config_code',
										'p2p_braintree_active',
                                        'p2p_paypal_client_ID',
                                        'p2p_paypal_secret',
										'p2p_paypal_enviroment',
                                        'p2p_nopayment_creditcard'
		);
		$section_payment = array('p2p_bigpromoter_payment',
								'Payment Settings',
								'p2p_bigpromoter_payment_section_text',
								'p2p_bigpromoter');
		$this->registerSettings($settings_payment,$section_payment);

		//register MAIL Settings
		$settings_mail = array('p2p_email',
                        'p2p_email_name',
				       'p2p_pass',
				       'p2p_smtpsecure',
				       'p2p_host',
				       'p2p_port',
                       'p2p_email_debug',
                       'p2p_email_admin',
                       'p2p_email_signature',
                       'p2p_email_color_bg',
                       'p2p_email_color_txt',
				       'email_receive_1',
				       'email_receive_2',
				       'email_receive_3',
				       'email_receive_name_1',
				       'email_receive_name_2',
				       'email_receive_name_3');        
		$section_mail = array('p2p_bigpromoter_email',
				      'E-mail Settings',
				      'p2p_bigpromoter_email_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_mail,$section_mail);		
		
		//register SERVICE Settings
		$settings_service = array('service_others');
		$section_service = array('p2p_bigpromoter_service',
				      'Service Settings',
				      'p2p_bigpromoter_service_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_service,$section_service);	
    		
		//register FLEET Settings
		$settings_service = array('increase_ride');
		$section_service = array('p2p_bigpromoter_fleet',
				      'Fleet Settings',
				      'p2p_bigpromoter_fleet_section_text',
				      'p2p_bigpromoter');
		$this->registerSettings($settings_service,$section_service);	
    
    }
	
	function includePage($page) {//Include Page on Admin
		include_once (dirname(__FILE__)."/view/{$page}.php");		
	}
	
	function bpSetting() {//See includePage
		$this->includePage('setting');
	}	

	function bpManageFleet() {//See includePage
		$this->includePage('manage_fleet');
	}

	function bpManageServices() {//See includePage
		$this->includePage('manage_services');
	}

	function bpManageReservation() {//See includePage
		$this->includePage('manage_reservation');
	}


//What happens when the plugin is activated	
	function p2p_activate() {
		
		//Create Tables
		include_once (dirname(__FILE__)."/model/model_admin.php");
		$model = new p2p_bp_ModelAdmin();	
		$model->activateCreateMainTable();
		$model->activateCreateCarsTable();
		$model->activateCreateServicesTable();

		//Insert Options
		add_option('start_map_lon', '-122.4194155');
		add_option('start_map_lat', '37.7749295');
		add_option('start_map_address', 'San Francisco/CA');
		add_option('start_map_zoom', '16');
		add_option('map_width', '400px');
		add_option('map_height', '400px');
		add_option('distance', '50');
		add_option('select_distance', 'miles');
		add_option('select_currency', '$');
		add_option('p2p_calendar_title', '%first_name% %last_name% - %email%');
		add_option('p2p_calendar_description_client', '
            Client Information:
            Name: %first_name% %last_name%
            Phone: %phone%
            E-mail: %email%
            Passenger: %npassenger%
            Luggage: %nluggage%
            Vehicle: %vehicletype%
            Service: %servicetype%
            Amount Paid: %amount_paid%
            Transaction Id: %transaction_id%        
        ');
		add_option('p2p_calendar_description_location', '
            When: %p_date% at %p_time_h%:%p_time_m%
            Instructions: %p_instructions%
             
            Pick Up Information:
            Address: %p_address% #%p_apt%
            City: %p_city%
            Zip Code: %p_zip%
            
            Drop Off Information:
            Address: %d_address% #%d_apt%
            City: %d_city%
            Zip Code: %d_zip%        
        ');
	}
	
	//What happens when the plugin is deactivated
    function p2p_deactivate() {
		//INSERT ACTIONS
    }
}
?>