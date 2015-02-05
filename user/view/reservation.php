<?php
    require_once( 'wp-blog-header.php' );
    include_once (dirname(__FILE__)."/../include.php");
    
    echo $control->color(); //Insert Custom Color
    
    //Get Car Info
    $chooseCar = $model->getCar($_GET['car']);
    //Get Price
    $price = $control->getTravelPrice($_GET['start'],$_GET['end'], $_GET['car']);
    //Get Locations Info
    $start = $control->gmapGeocode($_GET['start']);
    $end = $control->gmapGeocode($_GET['end']);
?>
    <div id="p2p_reservation" style="max-width: 940px;margin: auto;">
<?php
    
    if (!isset($_GET['end']) || !isset($_GET['start']) || !isset($_GET['car']) || empty($_GET['start']) || empty($_GET['end']) || empty($_GET['car'])) {
        echo 'Map is Empty<BR>';    
    } else {
        if (!empty($_POST)) {
            $er = $control->doReservation($_POST, $chooseCar, $price);
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
                <div class="w100p h40 left"><h5>Passenger Information</h5></div>
                <div class="w100p left">
                <?php
                    echo $control->startDiv();
                    echo $control->startDiv().$control->createInput('First Name','first_name','','user',$er['first_name']).$control->endDiv();
                    echo $control->startDiv().$control->createInput('Last Name','last_name','','user',$er['last_name']).$control->endDiv();
                    echo $control->startDiv().$control->createInput('Phone Number','phone','','phone',$er['phone']).$control->endDiv();
                    echo $control->startDiv().$control->createInput('E-mail Adress','email','','envelope',$er['email']).$control->endDiv();
                    echo $control->startDiv(50,'left').$control->createSelect('Number of Passenger','npassenger',1,$chooseCar->p2p_bp_cars_passenger,0,'users',$er['npassenger']).$control->endDiv();
                    echo $control->startDiv(50,'right').$control->createSelect('Number of Luggage','nluggage',1,$chooseCar->p2p_bp_cars_luggage,0,'suitcase',$er['nluggage']).$control->endDiv();
                    echo "<div class='space'></div>";
?>                    
                    <div class="w100p left">Vehicle</div>
                    <div class="w50p left"><img class="thumbCar" src="<?php echo $chooseCar->p2p_bp_cars_pic; ?>"/></div>
                    <div class="w50p right">
                        <span id="tx_vehicletype"><h2><?php echo $chooseCar->p2p_bp_cars_name; ?></h2></span><input type="hidden" name="vehicletype" value="<?php echo $chooseCar->p2p_bp_cars_name; ?>" /><BR>
                        <span id="tx_vehicletype_pass">Max Passengers: <?php echo $chooseCar->p2p_bp_cars_passenger; ?></span><BR>
                        <span id="tx_vehicletype_lugg">Max Luggage: <?php echo $chooseCar->p2p_bp_cars_luggage; ?></span><BR>
                    </div>
                    <div class='space'></div>
<?
                    echo $control->selectService((isset($_POST['servicetype'])?$_POST['servicetype']:null),$er['servicetype']);
                    echo $control->endDiv();
                ?>
                </div>
                <div class="space"></div>
                <div class="w100p left">
                <?php
                    echo $control->startDiv(50);
                    ?>
                    <div class="w100p h30 fcenter pTop10 colorSubTitle left"><strong>Pick-up Information</strong></div>
                    <div class="space"></div>
                    <?php
                        echo $control->startDiv().$control->createText('Address','p_address',$start['number'].' '.$start['street']).$control->endDiv();
                        echo $control->startDiv().$control->createInput('Apt/Suite','p_apt','','th-large').$control->endDiv();
                        echo $control->startDiv().$control->createText('City','p_city',$start['city'].'/'.$start['state_short']).$control->endDiv();
                        echo $control->startDiv().$control->createText('Zip Code','p_zip',$start['zip']).$control->endDiv();
                        echo $control->startDiv().$control->createInput('Date','p_date',(isset($_POST['p_date'])?$_POST['p_date']:null),'calendar',$er['p_date'],'has-feedback').$control->endDiv();
                        echo $control->startDiv().$control->createLabel('Time',$control->createDropDown ('p_time_h', 0, 23, true).'<span class="left">:</span>'.$control->createDropDown ('p_time_m', 0, 59, true),'time').$control->endDiv();
                        echo $control->endDiv();
                        echo $control->startDiv(50,'right');
                    ?>
                    <div class="w100p h30 fcenter pTop10 colorSubTitle right"><strong>Drop-off Information</strong></div>		    
                    <div class="space"></div>
                    <?php
                        echo $control->startDiv().$control->createText('Address','d_address',$end['number'].' '.$end['street']).$control->endDiv();
                        echo $control->startDiv().$control->createInput('Apt/Suite','d_apt','','th-large').$control->endDiv();
                        echo $control->startDiv().$control->createText('City','d_city',$end['city'].'/'.$end['state_short']).$control->endDiv();
                        echo $control->startDiv().$control->createText('Zip Code','d_zip',$end['zip']).$control->endDiv();
                        echo $control->endDiv();
                        echo $control->startDiv().$control->createInput('Special Instructions','p_instructions','','info-sign').$control->endDiv();
                    ?>
                </div>
                <div class="space"></div>
                <?php
                    $checked1 = $checked2 = null;
                    (isset($_POST['r']) && ($_POST['r'] == 1))?$checked1='checked':$checked2='checked';
                ?>
                <div class="w100p h40 left"><strong>Round Trip </strong>[ <input type="radio" id="r1" name="r" value="1" <?php echo $checked1; ?>> Yes | <input type="radio" id="r2" name="r" value="0" <?php echo $checked2; ?>> No ]</div>
                <script>
                
                    jQuery('input[name="r"]').click(function () {
                        var value = jQuery('input[name="r"]:checked', '#checkout').val();
                        if (value == 0) {
                            jQuery("#roundTrip").css("display", "none");
                            jQuery('#final_price').html('<?php echo get_option('select_currency').($price); ?>');
                        } else {
                            jQuery("#roundTrip").css("display", "block");
                            jQuery('#final_price').html('<?php echo get_option('select_currency').($price*2); ?>');
                        }
                    });
                    jQuery("#p_apt").change(function () {
                        jQuery("#tx_r_d_apt").html(jQuery("#p_apt").val());
                    });  
                    jQuery("#d_apt").change(function () {
                        jQuery("#tx_r_p_apt").html(jQuery("#d_apt").val());
                    });
                    jQuery(function() {
                        jQuery("#p_date").datepicker({
                            defaultDate: "+1d",
                            minDate: 0
                        });
                        jQuery("#r_p_date").datepicker({
                            defaultDate: "+1d",
                            minDate: 0
                        });
                    });
                    
                    jQuery(document).ready(function () {
                        jQuery("#p_date").after('<span class="glyphicon glyphicon-expand form-control-feedback adjust-date-picker" id="go_p_date"></span>');
                        jQuery("#r_p_date").after('<span class="glyphicon glyphicon-expand form-control-feedback adjust-date-picker" id="go_r_p_date"></span>');
                        jQuery("#go_p_date").click(function () {
                            jQuery("#p_date").focus();
                        });
                        jQuery("#go_r_p_date").click(function () {
                            jQuery("#r_p_date").focus();
                        });                        
                    });
                </script>
        
                <div id="roundTrip" class="w100p left" style="display: <?php ($checked1)?printf('block'):printf('none');?>;">
                    <div class="space"></div>
                    <div class="w100p color1 left">
                    <?php
                    echo $control->startDiv(50);
                    ?>
                    <div class="w100p h30 fcenter pTop10 colorSubTitle left"><strong>Pick-up Information</strong></div>
                    <div class="space"></div>
                    <?php
                        echo $control->startDiv().$control->createText('Address','r_p_address',$end['number'].' '.$end['street']).$control->endDiv();
                        echo $control->startDiv().$control->createText('Apt/Suite','r_p_apt','').$control->endDiv();
                        echo $control->startDiv().$control->createText('City','r_p_city',$end['city'].'/'.$end['state_short']).$control->endDiv();
                        echo $control->startDiv().$control->createText('Zip Code','r_p_zip',$end['zip']).$control->endDiv();
                        echo $control->startDiv().$control->createInput('Date','r_p_date',(isset($_POST['r_p_date'])?$_POST['r_p_date']:null),'calendar',$er['r_p_date'],'has-feedback').$control->endDiv();
                        echo $control->startDiv().$control->createLabel('Time',$control->createDropDown ('r_p_time_h', 0, 23, true).'<span class="left">:</span>'.$control->createDropDown ('r_p_time_m', 0, 59, true),'time').$control->endDiv();
                        echo $control->endDiv();
                        echo $control->startDiv(50,'right');
                    ?>
                    <div class="w100p h30 fcenter pTop10 colorSubTitle right"><strong>Drop-off Information</strong></div>		    
                    <div class="space"></div>
                    <?php
                        echo $control->startDiv().$control->createText('Address','r_d_address',$start['number'].' '.$start['street']).$control->endDiv();
                        echo $control->startDiv().$control->createText('Apt/Suite','r_d_apt','').$control->endDiv();
                        echo $control->startDiv().$control->createText('City','r_d_city',$start['city'].'/'.$start['state_short']).$control->endDiv();
                        echo $control->startDiv().$control->createText('Zip Code','r_d_zip',$start['zip']).$control->endDiv();
                        echo $control->endDiv();
                        echo $control->startDiv().$control->createInput('Special Instructions','r_p_instructions','','info-sign').$control->endDiv();
                    ?>
                    </div>
                </div>
<?php
        if (get_option('p2p_payment_type') == 'braintree') {
?>
            <div class="w100p h40 left"><h5>Credit Card Information (BrainTree)</h5></div>
            <div class="w100p left">
            <?php
                echo $control->startDiv();
                echo $control->startDiv().$control->createInput('Credit Card','card_num','','credit-card',0).$control->endDiv();
                echo $control->startDiv().$control->createInput('CVV','card_cvv','','credit-card',0).$control->endDiv();
                echo $control->startDiv().$control->createInput('Month','card_month','','calendar',0).$control->endDiv();
                echo $control->startDiv().$control->createInput('Year','card_year','','calendar',0).$control->endDiv();
                echo $control->endDiv();
            ?>
            </div>
<?php
            echo $control->paymentCallBrainTree();
        } else if (get_option('p2p_payment_type') == 'paypal') {
?>
            <div class="w100p h40 left"><h5>Credit Card Information (PayPal)</h5></div>
                <div class="w100p left">
                <?php
                    echo $control->startDiv();
                    echo $control->selectCartType($_POST['cardtype']);
                    echo $control->startDiv().$control->createInput('Credit Card','card_num','','credit-card',0).$control->endDiv();
                    echo $control->startDiv().$control->createInput('CVV','card_cvv','','credit-card',0).$control->endDiv();
                    echo $control->startDiv().$control->createInput('Month','card_month','','calendar',0).$control->endDiv();
                    echo $control->startDiv().$control->createInput('Year','card_year','','calendar',0).$control->endDiv();
                    echo $control->endDiv();
                ?>
                </div>
            </div>
<?php
        }
?>
                <div class="clear"></div>		
                <div class="w100p">
                    <input type="submit" value="submit" class="btn btn-primary pull-left"/>
                    <p class="bg-info" class="pull-left" style="margin-left: 100px; padding: 11px 20px; margin-bottom: 15px; width: 300px; text-align: center; border-radius: 5px;">Estimated Price: <span id="final_price"><?php echo get_option('select_currency').$price; ?></span></p>
                </div>
            </form>
            
<?php
        
    }
?>
    </div>
