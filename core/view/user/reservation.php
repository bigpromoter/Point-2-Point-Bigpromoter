<?php include_once(P2P_DIR_INCLUDE.'user.php'); ?>
<?php
    if (!isset($_GET['car']) || !isset($_GET['start']) || !isset($_GET['end'])) {die($control_shortcode->showMap());} //If There is no data, show Map    
    echo $control_util->color(); //Insert Custom Color
    $option = get_option('p2p_bp');
    $chooseCar = $control_fleet->getCar($_GET['car']);//Get Car Info
    $price = $control_map->getTravelPrice($_GET['start'],$_GET['end'], $chooseCar);//Get Price
    $start = $control_map->gmapGeocode($_GET['start']);//Get Start Location Info
    $end = $control_map->gmapGeocode($_GET['end']);//Get End Location Inf
?>

<div id="p2p_reservation" style="max-width: 940px;margin: auto;">
 <?php
    
    if (!isset($_GET['end']) || !isset($_GET['start']) || !isset($_GET['car']) || empty($_GET['start']) || empty($_GET['end'])) {
        echo 'Map is Empty<BR>';    
    } else {
        if (!empty($_POST)) {
            $er = $control_reservation->doReservation($_POST, $chooseCar, $price);
            $er['phone'] = null; //Doens't check Phone number
        } else {
            $er = array('first_name' => null,
                        'last_name' => null,
                        'phone' => null,
                        'email' => null,
                        'npassenger' => null,
                        'nluggage' => null,
                        'servicetype' => null,
                        'p_date' => null,
                        'r_p_date' => null
                        );   
        }
?>
 <form method="post" action="#" id="checkout">
  <div class="w100p fverdana f12px h40 left"> 
   <!-- Passenger Info -->
   <div class="w100p h40 fcenter pTop10 colorSubTitle left"><strong>Booking Information</strong></div>
   <div class="w100p left">
    <?php
                    echo $control_form->startDiv(60, 'left');
                    echo $control_form->startDiv(50,'left').$control_form->createInputReservation(__('First Name', P2P_TRANSLATE),'first_name','','user',$er['first_name']).$control_form->endDiv();
                    echo $control_form->startDiv(50,'right').$control_form->createInputReservation(__('Last Name', P2P_TRANSLATE),'last_name','','user',$er['last_name']).$control_form->endDiv();
                    echo $control_form->startDiv(50,'left').$control_form->createInputReservation(__('Phone Number', P2P_TRANSLATE),'phone','','phone',$er['phone']).$control_form->endDiv();
                    echo $control_form->startDiv(50,'right').$control_form->createInputReservation(__('E-mail Adress', P2P_TRANSLATE),'email','','envelope',$er['email']).$control_form->endDiv();
                    echo $control_form->startDiv(50,'left').$control_form->createSelect(__('Number of Passenger', P2P_TRANSLATE),'npassenger',1,(int)$chooseCar['nPass'],0,$er['npassenger']).$control_form->endDiv();
                    echo $control_form->startDiv(50,'right').$control_form->createSelect(__('Number of Luggage', P2P_TRANSLATE),'nluggage',1,(int)$chooseCar['nLugg'],0,$er['nluggage']).$control_form->endDiv();
					echo "<div class='space'></div>";
					echo $control_form->startDiv().$control_fleet->selectService((isset($_POST['servicetype'])?$_POST['servicetype']:null),$er['servicetype']).$control_form->endDiv();

                    echo "<div class='space'></div>";
?>
   </div>
   <div class="w40p right">
    <div class="w100p left ">
     <?php
                                $url_image = (strpos($option['basic']['reservation_page'],'https://') !== false)?str_replace('http://','https://',$chooseCar['pic']):$chooseCar['pic'];
                            ?>
     <img class="thumbCar margin5" src="<?php echo $url_image; ?>"/> </div>
    <div class="w100p right cars"> <span id="tx_vehicletype" class="boxTitle"><?php echo $chooseCar['body']; ?></span>
     <input type="hidden" name="vehicletype" value="<?php echo $chooseCar['body']; ?>" />
     <BR>
     <span id="tx_vehicletype_pass"><?php _e('Max', P2P_TRANSLATE); ?> <?php _e('Passengers', P2P_TRANSLATE); ?>: <?php echo $chooseCar['nPass']; ?></span><BR>
     <span id="tx_vehicletype_lugg"><?php _e('Max', P2P_TRANSLATE); ?> <?php _e('Luggage', P2P_TRANSLATE); ?>: <?php echo $chooseCar['nLugg']; ?></span><BR>
    </div>
    <div class='space'></div>
   </div>
   <div>
    <div class='space'></div>
    <?php echo $control_form->endDiv(); ?>
   </div>
  </div>
  <div class="space"></div>
  <div class="w100p left">
   <?php
                    echo $control_form->startDiv(50);
                    ?>
   <div class="w100p h40 fcenter pTop10 colorSubTitle left"><strong><?php _e('Pick-up Information', P2P_TRANSLATE); ?></strong></div>
   <div class="space"></div>
   <?php
                        echo $control_form->startDiv().$control_form->createText(__('Address', P2P_TRANSLATE),'p_address',$start['number'].' '.$start['street']).$control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createInputReservation(__('Apt/Suite', P2P_TRANSLATE),'p_apt','','th-large').$control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createText(__('City', P2P_TRANSLATE),'show_p_city',$start['city'].'/'.$start['state_short']).$control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createText(__('Zip Code', P2P_TRANSLATE),'p_zip',$start['zip']).$control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createInputReservation(__('Date', P2P_TRANSLATE),'p_date',(isset($_POST['p_date'])?$_POST['p_date']:null),'calendar',$er['p_date'],'has-feedback').$control_form->endDiv();
                        $s_am = $s_pm = '';
                        (isset($_POST['p_time_ampm']) && strtoupper($_POST['p_time_ampm'] != 'AM'))?$s_pm='selected':$s_am='selected';
                        echo $control_form->startDiv().$control_form->createLabel('Time',$control_form->createDropDown ('p_time_h', 1, 12, true).'<span class="left">:</span>'.$control_form->createDropDown ('p_time_m', 0, 59, true).'<select name="p_time_ampm" id="p_time_ampm" class="p2p_reservation_select"><option value="AM" '.$s_am.'>AM</option><option value="PM" '.$s_pm.'>PM</option></select>','time').$control_form->endDiv();
                        echo $control_form->endDiv();
                        echo $control_form->startDiv(50,'right');
                    ?>
   <input type="hidden" name="p_city" value="<?php echo $start['city']; ?>" />
   <input type="hidden" name="p_state" value="<?php echo $start['state_short']; ?>" />
   <div class="w100p h40 fcenter pTop10 colorSubTitle right"><strong><?php _e('Drop-off Information', P2P_TRANSLATE); ?></strong></div>
   <div class="space"></div>
   <?php
                        echo $control_form->startDiv().$control_form->createText(__('Address', P2P_TRANSLATE),'d_address',$end['number'].' '.$end['street']).$control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createInputReservation(__('Apt/Suite', P2P_TRANSLATE),'d_apt','','th-large').$control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createText(__('City', P2P_TRANSLATE),'show_d_city',$end['city'].'/'.$end['state_short']).$control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createText(__('Zip Code', P2P_TRANSLATE),'d_zip',$end['zip']).$control_form->endDiv();
                        echo $control_form->endDiv();
                        echo $control_form->startDiv().$control_form->createInputReservation(__('Special Instructions', P2P_TRANSLATE),'p_instructions','','info-sign').$control_form->endDiv();
                    ?>
   <input type="hidden" name="d_city" value="<?php echo $end['city']; ?>" />
   <input type="hidden" name="d_state" value="<?php echo $end['state_short']; ?>" />
   <div class="space"></div>
  </div>
  <?php if ($option['extra']['enabled']) { ?>
  <div class="w100p">
   <div class=" margin5">
    <div class="w100p h40 fcenter pTop10 colorSubTitle left"><strong><?php _e('Special Request', P2P_TRANSLATE); ?></strong></div>
    <div class="space"></div>
    <?php if ($option['extra']['car_seat']) { ?>
    <?php $checked = (isset($_POST['car_seat'])?'checked':''); ?>
    <?php $extra_car_seat_value = (is_numeric($option['extra']['car_seat_value'])?$option['extra']['car_seat_value']:0); ?>
    <div>
     <input type="checkbox" name="car_seat" id="car_seat" value="<?php echo $extra_car_seat_value; ?>" <?php echo $checked; ?>>
     Car Seat (<?php echo $option['basic']['select_currency'].number_format($extra_car_seat_value, 2, '.', ''); ?>)<br>
    </div>
    <?php } ?>
   </div>
  </div>
  <?php } ?>
  <div class="space"></div>
  <?php
                    $checked1 = $checked2 = null;
                    (isset($_POST['r']) && ($_POST['r'] == 1))?$checked1='checked':$checked2='checked';
                ?>
  <div class="w100p h40 fcenter pTop10 colorSubTitle left"><strong><?php _e('Round Trip', P2P_TRANSLATE); ?> </strong>[
   <input type="radio" id="r1" name="r" value="1" <?php echo $checked1; ?>>
   Yes |
   <input type="radio" id="r2" name="r" value="0" <?php echo $checked2; ?>>
   No ]</div>
  <div id="roundTrip" class="w100p left" style="display: <?php ($checked1)?printf('block'):printf('none');?>;">
   <div class="space"></div>
   <div class="w100p color1 left">
    <?php
                        echo $control_form->startDiv(50);
                        ?>
    <div class="w100p h40 fcenter pTop10 colorSubTitle left"><strong>Pick-up Information</strong></div>
    <div class="space"></div>
    <?php
                            echo $control_form->startDiv().$control_form->createText(__('Address',P2P_TRANSLATE),'r_p_address',$end['number'].' '.$end['street']).$control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createText(__('Apt/Suite',P2P_TRANSLATE),'r_p_apt','').$control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createText(__('City',P2P_TRANSLATE),'r_p_city',$end['city'].'/'.$end['state_short']).$control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createText(__('Zip Code',P2P_TRANSLATE),'r_p_zip',$end['zip']).$control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createInputReservation(__('Date',P2P_TRANSLATE),'r_p_date',(isset($_POST['r_p_date'])?$_POST['r_p_date']:null),'calendar',$er['r_p_date'],'has-feedback').$control_form->endDiv();
                            $s_r_am = $s_r_pm = '';
                            (isset($_POST['r_p_time_ampm']) && strtoupper($_POST['r_p_time_ampm'] != 'AM'))?$s_r_pm='selected':$s_r_am='selected';
                            echo $control_form->startDiv().$control_form->createLabel('Time',$control_form->createDropDown ('r_p_time_h', 1, 12, true).'<span class="left">:</span>'.$control_form->createDropDown ('r_p_time_m', 0, 59, true).'<select name="r_p_time_ampm" id="r_p_time_ampm" class="p2p_reservation_select"><option value="AM" '.$s_r_am.'>AM</option><option value="PM" '.$s_r_pm.'>PM</option></select>','time').$control_form->endDiv();
                            echo $control_form->endDiv();
                            echo $control_form->startDiv(50,'right');
                        ?>
    <div class="w100p h40 fcenter pTop10 colorSubTitle right"><strong><?php _e('Drop-off Information', P2P_TRANSLATE); ?></strong></div>
    <div class="space"></div>
    <?php
                            echo $control_form->startDiv().$control_form->createText(__('Address',P2P_TRANSLATE),'r_d_address',$start['number'].' '.$start['street']).$control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createText(__('Apt/Suite',P2P_TRANSLATE),'r_d_apt','').$control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createText(__('City',P2P_TRANSLATE),'r_d_city',$start['city'].'/'.$start['state_short']).$control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createText(__('Zip Code',P2P_TRANSLATE),'r_d_zip',$start['zip']).$control_form->endDiv();
                            echo $control_form->endDiv();
                            echo $control_form->startDiv().$control_form->createInputReservation(__('Special Instructions',P2P_TRANSLATE),'r_p_instructions','','info-sign').$control_form->endDiv();
                        ?>
    <div class='space'></div>
   </div>
   <?php if ($option['extra']['enabled']) { ?>
   <div class="w100p">
    <div class="margin5">
     <div class="w100p h40 fcenter pTop10 colorSubTitle left"><strong><?php _e('Special Request', P2P_TRANSLATE); ?></strong></div>
     <div class='space'></div>
     <?php if ($option['extra']['car_seat']) { ?>
     <?php $checked = (isset($_POST['r_car_seat'])?'checked':''); ?>
     <div class="w100p">
      <input type="checkbox" name="r_car_seat" value="<?php echo $extra_car_seat_value; ?>" <?php echo $checked; ?>>
      Car Seat (<?php echo $option['basic']['select_currency'].number_format($extra_car_seat_value, 2, '.', ''); ?>)<br>
     </div>
     <?php } ?>
    </div>
   </div>
   <?php } ?>
  </div>
  <?php if ($option['basic']['insert_gratuity']) { 
                    $gratuity10 = (isset($_POST['gratuity']) && ($_POST['gratuity'] == 10))?'checked':'';
                    $gratuity15= (isset($_POST['gratuity']) && ($_POST['gratuity'] == 15))?'checked':'';
                    $gratuity18 = (isset($_POST['gratuity']) && ($_POST['gratuity'] == 18))?'checked':'';
                    $gratuity20 = (isset($_POST['gratuity']) && ($_POST['gratuity'] == 20))?'checked':'';
                    $gratuityOther = (isset($_POST['gratuity']) && ($_POST['gratuity'] == "other"))?'checked':'';
                    $gratuity0 = (isset($_POST['gratuity']) && ($_POST['gratuity'] == 0))?'checked':'';
                    $gratuity0 = (!isset($_POST['gratuity']))?'checked':$gratuity0;
                ?>
  <div class="w100p">
   <div class="">
    <div class='space'></div>
    <div class="w100p h40 fcenter pTop10 colorSubTitle left"><strong><?php _e('Gratuity', P2P_TRANSLATE); ?></strong></div>
    <div class='space'></div>
    <?php $price_gratuity = (isset($_POST['r']) && ($_POST['r'] == 1))?$price*2:$price; ?>
    <div class="p2p_bp_radio" style="text-align: center;">
     <input type="radio" id="gratuity10" name="gratuity" value="10" <?php echo $gratuity10; ?>>
     <label for="gratuity10">10% (<?php echo $option['basic']['select_currency']; ?><span id="span_g10"><?php echo round(($price_gratuity * 0.10),2); ?></span>)</label>
     <input type="radio" id="gratuity15" name="gratuity" value="15" <?php echo $gratuity15; ?>>
     <label for="gratuity15">15% (<?php echo $option['basic']['select_currency']; ?><span id="span_g15"><?php echo round(($price_gratuity * 0.15),2); ?></span>)</label>
     <input type="radio" id="gratuity18" name="gratuity" value="18" <?php echo $gratuity18; ?>>
     <label for="gratuity18">18% (<?php echo $option['basic']['select_currency']; ?><span id="span_g18"><?php echo round(($price_gratuity * 0.18),2); ?></span>)</label>
     <input type="radio" id="gratuity20" name="gratuity" value="20" <?php echo $gratuity20; ?>>
     <label for="gratuity20">20% (<?php echo $option['basic']['select_currency']; ?><span id="span_g20"><?php echo round(($price_gratuity * 0.20),2); ?></span>)</label>
     <input type="radio" id="gratuity0" name="gratuity" value="0" <?php echo $gratuity0; ?>>
     <label  for="gratuity0"><?php _e('No Gratuity', P2P_TRANSLATE); ?></label>
    </div>
    <div class='space'></div>
    <div class="p2p_bp_radio" style="text-align: center;">
     <input type="radio" id="gratuityOther" name="gratuity" value="other" <?php echo $gratuityOther; ?>>
     <label style=" border-radius: 5px;" for="gratuityOther"><?php _e('Yout Gratuity', P2P_TRANSLATE); ?>: <?php echo $option['basic']['select_currency']; ?>
      <input type="text" name="gratuityOther_input" id="gratuityOther_input" value="<?php echo (isset($_POST['gratuityOther_input'])?$_POST['gratuityOther_input']:''); ?>"  class='w100'/>
     </label>
    </div>
   </div>
   <script>
        jQuery('#gratuityOther_input').click(function () {
            jQuery('#gratuityOther').prop('checked', true);
        });
   </script>
   <div class='space'></div>
  </div>
  <?php } ?>
  <?php
    $final_price = (isset($_POST['r']) && ($_POST['r'] == 1))?$price*2:$price;
    if (isset($_POST['gratuity']) && ($option['basic']['insert_gratuity'])) {
        if (is_numeric($_POST['gratuity'])) {
            $final_price = round(($final_price * (1 + ($_POST['gratuity']/100))),2);
        } else {
            if (is_numeric($_POST['gratuityOther_input'])) {
                $final_price = $final_price + $_POST['gratuityOther_input'];
            }
        }
    }
    $final_price += (isset($_POST['car_seat']))?$extra_car_seat_value:0;
    $final_price += (isset($_POST['r_car_seat']))?$extra_car_seat_value:0;
?>
  <div class="clear"></div>
  <div class="w100p p2p-final-price margin5">
   <p class="bg-info pull-left p2p_bp_alert p2p_bp_info w100p" style="zoom: 150%"><?php _e('Estimated Price', P2P_TRANSLATE); ?>: <span id="final_price"><?php echo $option['basic']['select_currency'].$final_price; ?></span></p>
  </div>
<?php
            if (($option['payment']['type'] != 'no') || (($option['payment']['type'] == 'no') && $option['payment']['nopayment_creditcard'])) {
?>
                <div>
                    <div class="w100p h40 fcenter pTop10 colorSubTitle left" ><strong><?php _e('Credit Card Information', P2P_TRANSLATE); ?> <?php echo ($option['payment']['type'] != 'no')?'('.ucwords($option['payment']['type']).')':''; ?></strong></div>
                    <div class="space"></div>
                    <div class="w100p left">
                    <?php
                        echo $control_form->startDiv();
                        echo $control_payment->selectCartType(isset($_POST['cardtype'])?$_POST['cardtype']:null);
                        echo $control_form->startDiv().$control_form->createInputReservation(__('Credit Card', P2P_TRANSLATE),'card_num','','credit-card',0).$control_form->endDiv();
                        echo $control_form->startDiv(50,'left').$control_form->createInputReservation(__('CVV', P2P_TRANSLATE),'card_cvv','','credit-card',0).$control_form->endDiv();
                        echo $control_form->startDiv(50,'right').$control_form->createInputReservation(__('Zip Code', P2P_TRANSLATE),'zip_code','','envelope',0).$control_form->endDiv();
                        echo $control_form->startDiv(50,'left').$control_form->createInputReservation(__('Month', P2P_TRANSLATE),'card_month','','calendar',0).$control_form->endDiv();
                        echo $control_form->startDiv(50,'right').$control_form->createInputReservation(__('Year', P2P_TRANSLATE),'card_year','','calendar',0).$control_form->endDiv();
                    
                        echo $control_form->endDiv();
                    ?>
                    </div>
                </div>
<?php
            }
?>
                <div class="space"></div>		
                <div class="w100p">
                    <input type="submit" value="<?php _e('Send Reservation', P2P_TRANSLATE); ?>" class="left"/>
                </div>
                 <div class="clear"></div>	
            </form>
<?php
        if ($option['payment']['type'] == 'braintree') echo $control_payment->paymentCallBrainTree();
    }
?>
</div>
<script>
    jQuery(document).ready(function () {
        reservation(<?php echo $price; ?>);
    });
    </script>