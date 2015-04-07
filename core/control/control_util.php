<?php
class P2P_Util {
    private $option;
	function __construct() {
        $this->option = get_option('p2p_bp');
	}
    
    //Access file
    function fileGetContentsCurl($site_url){
            $ch = curl_init();
            $timeout = 10;
            curl_setopt ($ch, CURLOPT_URL, $site_url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
            return $file_contents;
    }
    
    function fileGetContents($site_url) {
        return file_get_contents($site_url);
    }
    
    function changeDate($date, $dbFromUs = false) {
        if ($dbFromUs) {
            $d = explode('-',$date);
            if (count($d) < 3) return false;
            $newD = $d[1].'/'.$d[2].'/'.$d[0];        
        } else {
            $d = explode('/',$date);
            if (count($d) < 3) return false;
            $newD = $d[2].'-'.$d[0].'-'.$d[1];
        }
            return $newD;
    }
        
    function validateDate($date) {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) return true;
        else return false;    
    }    
    
    
    function color () {
        $output = '';
        $output .= '<style type="text/css">'.PHP_EOL;
        $output .= $this->option['color']['custom_css'].PHP_EOL;
        if ($this->option['color']['active'] == 1) {
            //Change Label
            $output .= '.p2p_bp_map > div > label {'.PHP_EOL;
            $output .= '    color: '.$this->option['color']['label_color'].';'.PHP_EOL;
            $output .= '    background: '.$this->option['color']['label_background'].';'.PHP_EOL;
            if ($this->option['color']['label_border'] == 1) 
                $output .= '    border: 1px solid '.$this->option['color']['label_border_color'].';'.PHP_EOL;
            else
                $output .= '    border: 0px;';
            $output .= '}'.PHP_EOL;
            //Change Input
            $output .= '.p2p_bp_map > div > input {'.PHP_EOL;
            $output .= '    color: '.$this->option['color']['input_color'].';'.PHP_EOL;
            $output .= '    background: '.$this->option['color']['input_background'].';'.PHP_EOL;
            if ($this->option['color']['input_border'] == 1) 
                $output .= '    border: 1px solid '.$this->option['color']['input_border_color'].';'.PHP_EOL;
            else
                $output .= '    border: 0px;';
            $output .= '}'.PHP_EOL;
            //Change Button
            $output .= '.p2p_bp_map > div > button {'.PHP_EOL;
            $output .= '    color: '.$this->option['color']['button_color'].';'.PHP_EOL;
            $output .= '    background: '.$this->option['color']['button_background'].';'.PHP_EOL;
            if ($this->option['color']['button_border'] == 1) 
                $output .= '    border: 1px solid '.$this->option['color']['button_border_color'].';'.PHP_EOL;
            else
                $output .= '    border: 0px;';
            $output .= '}'.PHP_EOL;
        } 
        $output .= '</style>'.PHP_EOL;
        return $output;
    }
    
   function sendMailMessage($info, $to = 'admin') {
        $email_header =  ($this->option['email']['curl'])?$this->fileGetContentsCurl(P2P_DIR_EMAIL_TEMPLATE.'header.html'):$this->fileGetContents(P2P_DIR_EMAIL_TEMPLATE.'header.html');;
        $email_main = ($this->option['email']['curl'])?$this->fileGetContentsCurl(P2P_DIR_EMAIL_TEMPLATE.strtolower($to).'.html'):$this->fileGetContents(P2P_DIR_EMAIL_TEMPLATE.strtolower($to).'.html');
        $email_round_trip = ($this->option['email']['curl'])?$this->fileGetContentsCurl(P2P_DIR_EMAIL_TEMPLATE.'round_trip.html'):$this->fileGetContents(P2P_DIR_EMAIL_TEMPLATE.'round_trip.html');
        $email_footer = ($this->option['email']['curl'])?$this->fileGetContentsCurl(P2P_DIR_EMAIL_TEMPLATE.'footer.html'):$this->fileGetContents(P2P_DIR_EMAIL_TEMPLATE.'footer.html');

        $output = '';
        $output .= $email_header;
        $output .= $email_main;
        
        $output = ($this->option['payment']['type'] == 'no' && $this->option['payment']['nopayment_creditcard'] && $to == 'admin')?str_replace("%card_info%", __('Card Information',P2P_TRANSLATION).': '.$info['card_info'], $output):str_replace("%card_info%", "", $output);

        $info['signature'] = $this->option['email']['signature'];
        $info['color_bg'] = (strlen($this->option['email']['color_bg']))?$this->option['email']['color_bg']:"#000";
        $info['color_txt'] = (strlen($this->option['email']['color_txt']))?$this->option['email']['color_txt']:"#FFF";
        
        $info['p_time'] = date('g:i A',strtotime($info['p_time_h'].':'.$info['p_time_m'].':00'));
        $info['r_p_time'] = date('g:i A',strtotime($info['r_p_time_h'].':'.$info['r_p_time_m'].':00'));

        if ($info['r'] == 1) {
            $fields = array('r_p_date','r_p_time','r_p_instructions', 'signature','color_bg','color_txt','r_extra');
            $output .= $email_round_trip;
            foreach ($fields as $field) {
                if (isset($info[$field])) $output = str_replace("%{$field}%", $info[$field], $output);
            }
        }
        
        $output .= $email_footer;
        
        $info['trip'] = $this->option['basic']['select_currency'].number_format((float)$info['trip'], 2, '.', '');
        $info['final_price'] = $this->option['basic']['select_currency'].number_format((float)$info['final_price'], 2, '.', '');
        $info['gratuity'] = $this->option['basic']['select_currency'].number_format((float)$info['gratuity'], 2, '.', '');
        
        $fields = array('first_name','last_name','phone','email','npassenger','nluggage','vehicletype','servicetype','p_address','p_apt','p_city','p_state','p_zip','p_date','p_time','p_instructions','d_address','d_apt','d_city','d_state','d_zip','trip','final_price','gratuity','signature','color_bg','color_txt',  'extra');        
        foreach ($fields as $field) {
            if (isset($info[$field])) $output = str_replace("%{$field}%", $info[$field], $output);
        }
        
        return $output;
    }
    
    function sendMail ($info, $to = 'admin') {
        $subject = ($to == 'admin')?__("You got a new Reservation",P2P_TRANSLATE):__("About your reservation",P2P_TRANSLATE);
        $message = $this->sendMailMessage($info, $to);
        
        // SMTP email sent
        require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/class-smtp.php';
        $phpmailer = new PHPMailer();

        $phpmailer->SMTPDebug 	= ($this->option['email']['debug'])?2:0; //0 for release; 2 for debug

        $phpmailer->Username  	= $this->option['email']['address'];
        $phpmailer->Password  	= $this->option['email']['pass'];
        
        if ($to == 'admin') {
            $phpmailer->AddAddress($this->option['email']['address'], $this->option['email']['name']);
            if (($this->option['email']['address'] != get_option('admin_email')) && ($this->option['email']['admin'])) $phpmailer->AddAddress(get_option('admin_email'), 'Admin');
            if ($this->option['email']['receive_1']) $phpmailer->AddAddress($this->option['email']['receive_1'], $this->option['email']['receive_name_1']);
            if ($this->option['email']['receive_2']) $phpmailer->AddAddress($this->option['email']['receive_2'], $this->option['email']['receive_name_2']);
            if ($this->option['email']['receive_3']) $phpmailer->AddAddress($this->option['email']['receive_3'], $this->option['email']['receive_name_3']);
        } else {$phpmailer->AddAddress($info['email'], $info['first_name'].' '.$info['last_name']);}
        
        $phpmailer->IsSMTP(); // telling the class to use SMTP

        if (strtolower($this->option['email']['smtpsecure']) != 'no') {
            $phpmailer->SMTPAuth = true;
            $phpmailer->SMTPSecure	= $this->option['email']['smtpsecure'];
        }
        $phpmailer->Host        = $this->option['email']['host']; // SMTP server
        $phpmailer->Port	    = $this->option['email']['port'];
        
        $phpmailer->Subject     = $subject;
        $phpmailer->Body        = $message;	//HTML Body
        if ($to == 'admin') $phpmailer->SetFrom($info['email'],$info['first_name'].' '.$info['last_name']);
        else $phpmailer->SetFrom($this->option['email']['address'],$this->option['email']['name']);
        $phpmailer->MsgHTML($message);
        $phpmailer->Priotity	= 1;
        $phpmailer->AltBody     = __("To view the message, please use an HTML compatible email viewer!",P2P_TRANSLATE); // optional, comment out and test
        
        $result = '';
        if ($phpmailer->Send()) {
            $result['sent'] = true;
            $result['error'] = '';
        } else {
            $result['sent'] = false;
            $result['error'] = $phpmailer->ErrorInfo;        
        }
        
        return $result;
    }
    
    function settings($settings) {
        if (isset($settings)) {
            if ($settings == true) echo '<div id="alert" class="p2p_bp_alert p2p_bp_success">'.__('Settings saved', P2P_TRANSLATE).'</div>';
            else echo '<div id="alert" class="p2p_bp_alert p2p_bp_error">'.__('Settings NOT saved', P2P_TRANSLATE).'</div>';
            
            echo "<script>jQuery('#alert').delay(2000).fadeOut(1000);</script>";
        }
    }
    
    function radioCheck($type, $form, $value_yes = 1) {
        if ($type == $value_yes) {
            $result['checked_no_'.$form] = $result['active_no_'.$form] = '';            

            $result['checked_yes_'.$form] = 'checked';
            $result['active_yes_'.$form] = 'active';
            $result['display_'.$form] = 'block';
        }
        else {
            $result['checked_yes_'.$form] = $result['active_yes_'.$form] = '';            
            $result['checked_no_'.$form] = 'checked';
            $result['active_no_'.$form] = 'active';
            $result['display_'.$form] = 'none';
        }
        return $result;
    }
}
?>