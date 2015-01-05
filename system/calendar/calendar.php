<?php
class GoogleCalendar {
	function __construct() {
		$this->local_time	= current_time('timestamp');
		require_once dirname(__FILE__).'/../../system/calendar/google/Google_Client.php';
		require_once dirname(__FILE__).'/../../system/calendar/google/contrib/Google_CalendarService.php';
    }
	/**
	 * Connects to GCal API
	 */
	function connect() {
		// Disallow faulty plugins to ruin what we are trying to do here
		@ob_start();
    
		require_once dirname(__FILE__).'/../../system/calendar/google/Google_Client.php';

		$this->client = new Google_Client();
		$this->client->setApplicationName("Appointments");
		$this->client->setUseObjects(true);
		$key = file_get_contents(plugins_url('p2p_bigpromoter/') . '/system/calendar/google/key/'.get_option('p2p_calendar_keyfilename').'.p12');
		$this->client->setAssertionCredentials(new Google_AssertionCredentials(
			get_option('p2p_calendar_serviceemail'),
			array('https://www.googleapis.com/auth/calendar'),
			$key)
		);
    
		$this->service = new Google_CalendarService($this->client);
		return true;
	}

    /**
	 * Inserts an appointment to the selected calendar as event
	 * @param app_id: Appointment ID to be inserted
	 * @test: Insert a test appointment
	 */
	function insert_event($info, $car, $payment_info, $test = 0, $round_trip=0, $leg = 0) {
		if ( !$this->connect() )
			return false;

		global $wpdb;
        
        //Insert Round Trip on Calendar

        $app = new stdClass();
        $app->worker = 0;
        $app->price = 0;
        if ($test) {
        
            // Find time difference from Greenwich as GCal asks UTC
            if ( !current_time('timestamp') ) $tdif = 0;
            else $tdif = current_time('timestamp') - time();
            $app->name = 'Test';
            $app->phone = 'Test';
            $app->address = 'Local Test';
            $app->city = 'City Teste';
            $app->start = date('Y-m-d H:i:s', time() + $tdif);
            $app->end = date('Y-m-d H:i:s', time() + 1800 + $tdif);
            
            $app->color = 1;
            $app->note = 'THIS IS A TEST!';
            $app->email = 'test@test.com';
            $app->summary = 'TEST';
        } else {
            $app->name = $info['first_name'];
            $app->phone = $info['last_name'];
            $app->email = $info['email'];
            $app->color = $car->p2p_bp_cars_color;
            if ($round_trip && $leg == 2) {
                $app->address = $info['d_address'];
                $app->city = $info['d_city'];
                if ($info['r_p_time_h'] == 0 && $info['r_p_time_m'] == 0) {$info['r_p_time_m'] = 1;}
                $app->start = $this->changeDate($info['r_p_date']).' '.$info['r_p_time_h'].':'.$info['r_p_time_m'];
                $app->end = $this->changeDate($info['r_p_date']).' '.$info['r_p_time_h'].':'.$info['r_p_time_m'];
            } else {
                $app->address = $info['p_address'];
                $app->city = $info['p_city'];
                if ($info['p_time_h'] == 0 && $info['p_time_m'] == 0) {$info['p_time_m'] = 1;}
                $app->start = $this->changeDate($info['p_date']).' '.$info['p_time_h'].':'.$info['p_time_m'];
                $app->end = $this->changeDate($info['p_date']).' '.$info['p_time_h'].':'.$info['p_time_m'];
            }
                $app->note = $this->createNote('note',$info, $payment_info, $round_trip, $leg);            
            $app->summary = $this->createNote('summary',$info, $payment_info, $round_trip, $leg); 
        }
        $app->service = 'service';

		// Create Event object and set parameters
		$this->set_event_parameters($app);
		// Insert event
		try {
			$createdEvent = $this->service->events->insert($this->get_selected_calendar(), $this->event );
            if ($createdEvent && !is_object($createdEvent) && class_exists('Google_CalendarListEntry')) $createdEvent = new Google_CalendarListEntry($createdEvent);

            return true;
		} catch (Exception $e) {
			//echo $e->getMessage();
			return false;
		}
	}
    
    /**
	 * Creates a Google Event object and set its parameters
	 * @param app: Appointment object to be set as event
	 */
	function set_event_parameters( $app ) {

		// Find time difference from Greenwich as GCal asks UTC
		if ( !current_time('timestamp') )
			$tdif = 0;
		else
			$tdif = current_time('timestamp') - time();

		$start = new Google_EventDateTime();
		$start->setDateTime( date( "Y-m-d\TH:i:s\Z", strtotime($app->start)  - (get_option('gmt_offset') * 60 * 60)));

		$end = new Google_EventDateTime();
		$end->setDateTime( date( "Y-m-d\TH:i:s\Z", strtotime($app->end) - (get_option('gmt_offset') * 60 * 60)));

		// An email is always required
		$email = $app->email;

		$attendee1 = new Google_EventAttendee();
		$attendee1->setEmail( $email );
		$attendees = array($attendee1);

		$this->event = new Google_Event( );
		$this->event->setSummary( $app->summary );
		$this->event->setLocation( $app->address.','.$app->city );
		$this->event->setStart( $start );
		$this->event->setEnd( $end );
		$this->event->setDescription($app->note);
		$this->event->attendees = $attendees;
        $this->event->colorId = $app->color;
        
        return $this->event;
	}
    
	/**
	 * Return GCal selected calendar ID
	 * @param worker_id: Optional worker ID whose data will be restored
	 * @return string
	 */
	function get_selected_calendar() {
		return get_option('p2p_calendar_used');
    }
    
    function changeDate($date) {
        $d = explode('/',$date);
        $newD = $d[2].'-'.$d[0].'-'.$d[1];
        return $newD;
    }
    function createNote($destiny, $info, $payment_info, $round_trip = 0, $leg = 0) {
        $message = '';
        
        $r_info = '';
        if ($round_trip) {
            if ($leg == 1) $r_info = '[RounTrip 1st Leg] ';
            else if ($leg == 2)  $r_info = '[RounTrip 2nd Leg] ';
            else  $r_info = '[RounTrip] ';
        }
        
        if ($destiny == 'summary') {
            $message = $r_info.get_option('p2p_calendar_title');
        } else if ($destiny == 'note') {
            if ($round_trip) {
                $message .= strtoupper($r_info);
                $message .= PHP_EOL.PHP_EOL;
            }
            $message .= get_option('p2p_calendar_description_client');
            $message .= PHP_EOL.PHP_EOL;
            $message .= get_option('p2p_calendar_description_location');        
        } else {
            $message = 'Error';
        }
        
        if ($round_trip && $leg == 2) {
        
            $p_side = array('p_address','p_apt','p_city','p_zip');
            $d_side = array('d_address','d_apt','d_city','d_zip');
            $x_side = array('x_address','x_apt','x_city','x_zip');
            $message = str_replace($p_side,$x_side,$message);
            $message = str_replace($d_side,$p_side,$message);
            $message = str_replace($x_side,$d_side,$message);
        
            $replace = array('first_name','last_name','phone','email','npassenger',
                             'nluggage','vehicletype','servicetype','p_address','p_apt',
                             'p_city','p_zip','p_date','p_time_h','p_time_m','p_instructions',
                             'd_address','d_apt','d_city','d_zip');       
            
            $info['p_date'] = $this->changeDate($info['r_p_date']);
            $info['p_time_h'] = $info['r_p_time_h'];
            $info['p_time_m'] = $info['r_p_time_m'];
            
            foreach ($replace as $rep) {
                $message = str_replace('%'.$rep.'%',$info[$rep],$message);
            }        
        } else {
            $replace = array('first_name','last_name','phone','email','npassenger',
                             'nluggage','vehicletype','servicetype','p_address','p_apt',
                             'p_city','p_zip','p_date','p_time_h','p_time_m','p_instructions',
                             'd_address','d_apt','d_city','d_zip');       
            
            $info['p_date'] = $this->changeDate($info['p_date']);
            
            foreach ($replace as $rep) {
                $message = str_replace('%'.$rep.'%',$info[$rep],$message);
            }
        }
        
        $message = str_replace('%amount_paid%',$payment_info['paid'],$message);
        $message = str_replace('%transaction_id%',$payment_info['id'],$message);
        
        return $message;      
    }
}    
?>