<?php
class P2P_Form {

    private $option;
    private $control_util;
	function __construct() {
        $this->option = get_option('p2p_bp');
        include_once(P2P_DIR_CONTROL.'util.php');
        $this->control_util = new P2P_Util();
	}

    //Create Select from Array
    function selectArray($name, $values, $active='', $class='') {
        $output = "<select name=\"{$name}\" id=\"{$name}\" class=\"{$class}\">";
        foreach ($values as $v) {
                if ($v == $active) $output .= "<option value=\"{$v}\" selected>{$v}</option>";
                else $output .=  "<option value=\"{$v}\">{$v}</option>";
        }
        $output .= "</select>";
        return $output;
    }
    
    function createInput($name, $value, $class = '', $type = false) {
        return '<input type="'.(($type !== false)?$type:'text').'" id="'.$name.'" name="'.$name.'" value="'.$value.'"  class="'.$class.'"/>';
    }
    
    function createInputReservation($textcol, $namecol, $valuecol, $icon, $error = 0, $class = '') {
        (isset($_POST[$namecol]))?$valuecol = $_POST[$namecol]:$valuecol='';
        $class_error = ($error == 1)?'error_form':'';
    
        $output = '';
        $output .= '	<div class="w100p ">';
        $output .= '		<div class=" '.$class.' p2p_reservation_input">';
        if ($this->option['basic']['placeholder']) {
            $output .= '			<input class="'.$class_error.'" type="text" name="'.$namecol.'" id="'.$namecol.'" value="'.$valuecol.'" placeholder="'.$textcol.'" class="w100p">';
        } else {
            $output .= '			<span class="'.$class_error.'">'.$textcol.'</span>';        
            $output .= '			<input class="'.$class_error.'" type="text" name="'.$namecol.'" id="'.$namecol.'" value="'.$valuecol.'" class="w100p">';        
        }
        $output .= '		</div>';
        $output .= '	</div>';
    
        
        return $output;
    }
    
    function createTextArea($name, $value, $rows, $class = '') {
        return '<textarea id="'.$name.'" name="'.$name.'" class="'.$class.'" rows="'.$rows.'">'.$value.'</textarea>';
    }
    
    function createCheckBox($name, $check, $value, $class = '') {
        $checked = ($check?'checked':'');
        return '<input type="checkbox" id="'.$name.'" name="'.$name.'" value="'.$value.'" '.$checked.'/>';
    }
    
    function createRadioYN($name, $active, $id, $value = false, $label = false) {
            
        if (is_array($value) && count($value) == 2) {
            $value_yes = $value[0];
            $value_no = $value[1];
        } else {$value_yes = 1; $value_no = 0;}

        if (is_array($label) && count($label) == 2) {
            $label_yes = $label[0];
            $label_no = $label[1];
        } else {$label_yes = 'Yes'; $label_no = 'No';}
        
        $check = $this->control_util->radioCheck($active, 'check', $value_yes);
    ?>
        <div id="<?php echo $id; ?>" class="bp_switch">
            <input type="radio" name="<?php echo $name; ?>" id="<?php echo $id; ?>_yes" value='<?php echo $value_yes; ?>' <?php echo (isset($check['checked_yes_check']))?$check['checked_yes_check']:''; ?>>
                <label id="<?php echo $id; ?>_yes_label" for="<?php echo $id; ?>_yes"><?php _e($label_yes, P2P_TRANSLATE); ?></label>
            <input type="radio" name="<?php echo $name; ?>" id="<?php echo $id; ?>_no" value='<?php echo $value_no; ?>' <?php echo (isset($check['checked_no_check']))?$check['checked_no_check']:''; ?>>
                <label id="<?php echo $id; ?>_no_label" for="<?php echo $id; ?>_no"><?php _e($label_no, P2P_TRANSLATE); ?></label>
        </div>
    <?php
    }
    
    function timeZone($value) {
        $time = array(array('0000','UTC+0'),
                        array('+0020','UTC+0:20'),
                        array('+0030','UTC+0:30'),
                        array('+0100','UTC+1'),
                        array('+0200','UTC+2'),
                        array('+0300','UTC+3'),
                        array('+0330','UTC+3:30'),
                        array('+0400','UTC+4'),
                        array('+0430','UTC+4:30'),
                        array('+0451','UTC+4:51'),
                        array('+0500','UTC+5'),
                        array('+0530','UTC+5:30'),
                        array('+0540','UTC+5:40'),
                        array('+0545','UTC+5:45'),
                        array('+0600','UTC+6'),
                        array('+0630','UTC+6:30'),
                        array('+0700','UTC+7'),
                        array('+0720','UTC+7:20'),
                        array('+0730','UTC+7:30'),
                        array('+0800','UTC+8'),
                        array('+0830','UTC+8:30'),
                        array('+0845','UTC+8:45'),
                        array('+0900','UTC+9'),
                        array('+0930','UTC+9:30'),
                        array('+1000','UTC+10'),
                        array('+1030','UTC+10:30'),
                        array('+1100','UTC+11'),
                        array('+1130','UTC+11:30'),
                        array('+1200','UTC+12'),
                        array('+1245','UTC+12:45'),
                        array('+1300','UTC+13'),
                        array('+1345','UTC+13:45'),
                        array('+1400','UTC+14'),
                        array('-0025','UTC-0:25'),
                        array('-0100','UTC-1'),
                        array('-0200','UTC-2'),
                        array('-0230','UTC-2:30'),
                        array('-0300','UTC-3'),
                        array('-0330','UTC-3:30'),
                        array('-0400','UTC-4'),
                        array('-0430','UTC-4:30'),
                        array('-0500','UTC-5'),
                        array('-0600','UTC-6'),
                        array('-0700','UTC-7'),
                        array('-0800','UTC-8'),
                        array('-0900','UTC-9'),
                        array('-0930','UTC-9:30'),
                        array('-1000','UTC-10'),
                        array('-1100','UTC-11'));
        sort($time);
        $output = '';
        $output .= '<select id="p2p_calendar_timezone" name="p2p_calendar_timezone" class="w100">';
        foreach ($time as $t) {
            if ($t[0] == $value) $output .= "<option value='{$t[0]}' selected>{$t[1]}</option>";
            else $output .=  "<option value='{$t[0]}'>{$t[1]}</option>";
        
        }
        
        $output .= '</select>';
        
        return $output;
    }
    
    function valuesCalendar($field) {
        $values = array(array('first_name',     __('Client',P2P_TRANSLATE).': '. __('First Name',P2P_TRANSLATE)),
                        array('last_name',      __('Client',P2P_TRANSLATE).': '. __('Last Name',P2P_TRANSLATE)),
                        array('phone',          __('Client',P2P_TRANSLATE).': '. __('Phone',P2P_TRANSLATE)),
                        array('email',          __('Client',P2P_TRANSLATE).': '. __('E-mail',P2P_TRANSLATE)),
                        array('npassenger',     __('Client',P2P_TRANSLATE).': '. __('N Passenger',P2P_TRANSLATE)),
                        array('nluggage',       __('Client',P2P_TRANSLATE).': '. __('N Luggage',P2P_TRANSLATE)),
                        array('vehicletype',    __('Client',P2P_TRANSLATE).': '. __('Vehicle',P2P_TRANSLATE)),
                        array('servicetype',    __('Client',P2P_TRANSLATE).': '. __('Service',P2P_TRANSLATE)),
                        array('p_address',      __('Pick Up Info',P2P_TRANSLATE).': '. __('Address',P2P_TRANSLATE)),
                        array('p_apt',          __('Pick Up Info',P2P_TRANSLATE).': '. __('Apt/Suite',P2P_TRANSLATE)),
                        array('p_city',         __('Pick Up Info',P2P_TRANSLATE).': '. __('City/State',P2P_TRANSLATE)),
                        array('p_zip',          __('Pick Up Info',P2P_TRANSLATE).': '. __('Zip Code',P2P_TRANSLATE)),
                        array('p_date',         __('Pick Up Info',P2P_TRANSLATE).': '. __('Date',P2P_TRANSLATE)),
                        array('p_time_h%:%p_time_m',__('Pick Up Info',P2P_TRANSLATE).': '. __('Time',P2P_TRANSLATE)),
                        array('p_instructions', __('Pick Up Info',P2P_TRANSLATE).': '. __('Instructions',P2P_TRANSLATE)),
                        array('d_address',      __('Drop Off Info',P2P_TRANSLATE).': '. __('Address',P2P_TRANSLATE)),
                        array('d_apt',          __('Drop Off Info',P2P_TRANSLATE).': '. __('Apt/Suite',P2P_TRANSLATE)),
                        array('d_city',         __('Drop Off Info',P2P_TRANSLATE).': '. __('City/State',P2P_TRANSLATE)),
                        array('d_zip',          __('Drop Off Info',P2P_TRANSLATE).': '. __('Zip Code',P2P_TRANSLATE)),
                        array('amount_paid',    __('Payment',P2P_TRANSLATE).': '. __('Amount Paid',P2P_TRANSLATE)),
                        array('transaction_id', __('Payment',P2P_TRANSLATE).': '. __('Payment: Transaction Id',P2P_TRANSLATE)));
        $output = '';
        $output .= '<select id="'.$field.'_value" name="'.$field.'_value" class="w100p" style="margin-bottom: 5px;">';
        foreach ($values as $value) {
            $output .=  "<option value='%{$value[0]}%'>{$value[1]}</option>".PHP_EOL;
        }
        $output .= '</select>'.PHP_EOL;
        $output .= '<div id="'.$field.'_insert" class="p2p_bp_save p2p_bp_save_intern">'.__('Insert',P2P_TRANSLATE).'</div>'.PHP_EOL;
        $output .= '<script>'.PHP_EOL;
        $output .= 'jQuery("#'.$field.'_insert").click(function () {'.PHP_EOL;
        //$output .= '    var position = jQuery(".'.$field.'").getCursorPosition()'.PHP_EOL;
        $output .= '    var position = getCursorPosition(".'.$field.'")'.PHP_EOL;
        $output .= '    var content = jQuery(".'.$field.'").val();'.PHP_EOL;
        $output .= '    var newContent = content.substr(0, position) + jQuery("#'.$field.'_value").val() + content.substr(position);'.PHP_EOL;
        $output .= '    jQuery(".'.$field.'").val(newContent);'.PHP_EOL;
        $output .= '});'.PHP_EOL;
        $output .= '</script>'.PHP_EOL;
        
        return $output;
    }
    
    function paymentType($type) {
        $result['checked_paypal'] = $result['checked_braintree'] = $result['checked_no'] = '';
        if ($type == 'paypal') {
            $result['checked_paypal'] = 'checked';
            $result['active_paypal'] = 'active';
            $result['display_paypal'] = 'block';
            $result['display_braintree'] = $result['display_no'] = 'none';
            $result['active_braintree'] = $result['active_no'] = '';
        }
        else if ($type == 'braintree') {
            $result['checked_braintree'] = 'checked';
            $result['active_braintree'] = 'active';
            $result['display_braintree'] = 'block';
            $result['display_paypal'] = $result['display_no'] = 'none';
            $result['active_paypal'] = $result['active_no'] = '';
        }
        else {
            $result['checked_no'] = 'checked';
            $result['active_no'] = 'active';
            $result['display_no'] = 'block';
            $result['display_paypal'] = $result['display_braintree'] = 'none';
            $result['active_paypal'] = $result['active_braintree'] = '';
        }
        return $result;
    }
     
    function startDiv($width = 100, $side = 'left') {
        $output = '';
        $output .= '<div class="w'.$width.'p '.$side.' h40 pos0">';
        
        return $output;	
    }

    function endDiv() {
        $output = '';
        $output .= '</div>';
        
        return $output;	
    }

    
    function createLabel($title, $text, $class = '') {
        $output = '';
        $output .= '<span class="left">'.$title.':</span>';	
        $output .= '<span class="left '.$class.'">'.$text.'</span>';	
        
        return $output;
    }

    function createText($textcol, $namecol, $valuecol) {
        $output = '';
        $output .= '<div class="p2p_reservation_text">';
        $output .= '	<div class="w30p left">'.$textcol.':</div>';
        $output .= '	<div class="w70p right"><span id="tx_'.$namecol.'">'.$valuecol.'</span><input type="hidden" name="'.$namecol.'" value="'.$valuecol.'" /></div>';
        $output .= '</div>';
        return $output;
    }

    function createSelect($textcol, $namecol, $start, $end, $use_zero, $error = false) {
        $class_error = ($error == 1)?'error_form':'';
        $output = '';
        $output .= '	<div class="left p2p_reservation_select_label">'.$textcol.':</div>';
        $output .= $this->createDropDown($namecol, $start, $end, $use_zero, $class_error);

            
        return $output;
    }
    
   function createDropDown ($namecol, $start, $end, $use_zero = false, $class_error = false) {
        $output = '';
        $output .= '<select class="left p2p_reservation_select" name="'.$namecol.'" id="'.$namecol.'">';
        
        for ($i=$start;$i<=$end;$i++) {
            $zero='';
            (isset($_POST[$namecol]) && ($i == $_POST[$namecol]))?$selected = 'selected':$selected = '';
            (strlen($i) < 2 && $use_zero)?$zero='0':$zero='';
            $output .= '	<option value="'.$i.'"  '.$selected.'>'.$zero.$i.'</option>';
        }
        $output .= '</select>';
            
        return $output;	
    }
        

}
?>