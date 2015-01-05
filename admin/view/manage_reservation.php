<?php
include_once (dirname(__FILE__)."/../include.php");

$comp =''; //Search
if (isset($_POST['date_from'])) {
    $comp = 'WHERE '; 
    $from = ($control->validateDate($control->dbDate($_POST['date_from'])))?$control->dbDate($_POST['date_from']):'2000-01-01';
    $to = ($control->validateDate($control->dbDate($_POST['date_to'])))?$control->dbDate($_POST['date_to']):'2038-12-31';
    
    $comp .= "((p2p_bp_p_date BETWEEN '{$from}' AND '{$to}') OR (p2p_bp_r_p_date BETWEEN '{$from}' AND '{$to}'))";
    
    if ($_POST['is_provided'] != 2) {
        $comp .= " AND ((p2p_bp_done = '{$_POST['is_provided']}') OR ((p2p_bp_done_r = '{$_POST['is_provided']}') AND (p2p_bp_r = 1)))";
    }
}
$results = $model->getResults($model->reservation['table'],$comp);


?>
<div class="wrap">
<h2>Point to Point - Big Promoter</h2>
<div class="space"></div>
<div class="bgWhite left" style="max-width:940px;">
<div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion" href="#search">
            <div class="panel-heading" style="background-color: #D9EDF7">
                <h4 class="panel-title"><i class="glyphicon glyphicon-search"></i> Search</h4>
            </div>
        </a>
        <div id="search" class="panel-collapse collapse in">
            <div class="panel-body">
                <form method="POST" action="?page=p2p_bigpromoter_reservation">
                    <div class="row">
                        <div class="col-md-6 left h30 pos0">
                            <div class="w100p">
                                <div class="input-group margin-bottom-sm">
                                    <span class="input-group-addon glyphicon glyphicon-calendar pos1l"></span>
                                    <span class="input-group-addon pos1 pos1l">From</span>
                                    <input class="form-control pos1 hasDatepicker" type="text" name="date_from" id="date_from" value="<?php echo (isset($_POST['date_from']))?$_POST['date_from']:'';?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 left h30 pos0">
                            <div class="w100p">
                                <div class="input-group margin-bottom-sm">
                                    <span class="input-group-addon glyphicon glyphicon-calendar pos1l"></span>
                                    <span class="input-group-addon pos1 pos1l">To</span>
                                    <input class="form-control pos1 hasDatepicker" type="text" name="date_to" id="date_to" value="<?php echo (isset($_POST['date_to']))?$_POST['date_to']:'';?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        jQuery(function() {
                            jQuery("#date_from").datepicker({defaultDate: "-1d",changeMonth: true,changeYear: true});
                            jQuery("#date_to").datepicker({defaultDate: "+1d",changeMonth: true,changeYear: true});
                        });
                    </script>                    
                    <div style="width:100%; height: 5px;"></div>
                    <?php
                        $checked_all = ($_POST['is_provided'] == 2)?'checked':'';
                        $checked_provided = ($_POST['is_provided'] == 1)?'checked':'';
                        $checked_not_provided = ($_POST['is_provided'] == 0)?'checked':'';
                        
                        $active_all = ($_POST['is_provided'] == 2)?'active':'';
                        $active_provided = ($_POST['is_provided'] == 1)?'active':'';
                        $active_not_provided = ($_POST['is_provided'] == 0)?'active':'';
                    ?>
                    <div class="btn-group" data-toggle="buttons">
                        <label id="no" class="btn btn-primary <?php echo $active_all; ?>">
                            <input type="radio" name="is_provided" id="all" value='2' <?php echo $checked_all; ?>> All
                        </label>
                        <label id="paypal" class="btn btn-primary <?php echo $active_provided; ?>">
                            <input type="radio" name="is_provided"  id="provided" value='1' <?php echo $checked_provided; ?>> Provided
                        </label>
                        <label id="braintree" class="btn btn-primary <?php echo $active_not_provided; ?>">
                            <input type="radio" name="is_provided" id="notprovided" value='0' <?php echo $checked_not_provided; ?>> Not Provided
                        </label>
                    </div>
                    <div style="width:100%; height: 5px;"></div>
                    <button type="submit" class="btn btn-default">Submit</button>
                    
                </form>
            </div>
        </div>
    </div>
</div>
<?php
		foreach ($results as $result) {
			$reservationAptP = (strlen($result->p2p_bp_p_apt))?'#'.$result->p2p_bp_p_apt:'';
			$reservationAptD = (strlen($result->p2p_bp_d_apt))?'#'.$result->p2p_bp_p_apt:'';
            
            //Check Transportation Status
            $date = (strtotime($control->reverseDate($result->p2p_bp_p_date)) > strtotime('now'))?true:false;
            if ($date) $trip = ($result->p2p_bp_done)?'success':'info';
            else $trip = ($result->p2p_bp_done)?'success':'danger';
            
            $btTrip['done'] = ($trip == 'success')?'none':'display';
            $btTrip['undone'] = ($trip == 'success')?'display':'none';
            
            $date_r = (strtotime($control->reverseDate($result->p2p_bp_r_p_date)) > strtotime('now'))?true:false;
            if ($date_r) $trip_r = ($result->p2p_bp_done_r)?'success':'info';
            else $trip_r = ($result->p2p_bp_done_r)?'success':'danger';
            
            $btTrip['done_r'] = ($trip_r == 'success')?'none':'display';
            $btTrip['undone_r'] = ($trip_r == 'success')?'display':'none';
            
            //End Check
            
            
?>
	<div class="w100p carBox left" id="reservation<?php echo $result->p2p_bp_id; ?>">
		<div class="w100p btn-danger fleft left divDelete" id="divDelete<?php echo $result->p2p_bp_id; ?>" style="display: none;">
			<div class="space"></div>
			<div class="col-md-10 left">
				<div class="input-group margin-bottom-sm">
					Are you sure you want to delete this reservation? <button type="button" class="btn btn-danger" id="confirm<?php echo $result->p2p_bp_id; ?>" value="<?php echo $result->p2p_bp_id; ?>">Delete</button>
				</div>
			</div>
			<div class="col-md-2 right">
				<div class="input-group margin-bottom-sm">
					<button type="button" class="btn btn-std" id="close<?php echo $result->p2p_bp_id; ?>" value="<?php echo $result->p2p_bp_id; ?>">Close</button>
				</div>
			</div>			
			<div class="space"></div>
		</div>
		<div class="space5"></div>
        <div class="panel panel-primary">
            <div class="input-group margin-bottom-sm right buttonDelete">
                <button type="button" class="btn btn-danger" id="delete<?php echo $result->p2p_bp_id; ?>" value="<?php echo $result->p2p_bp_id; ?>">Delete</button>
            </div>                    
            <div class="panel-body">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">[Id: <?php echo $result->p2p_bp_id; ?>] Service Information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-4 col-xs-12 h30 text-left left">Vehicle Type: <strong><?php echo $result->p2p_bp_vehicletype; ?></strong></div>
                        <div class="col-md-4 col-xs-12 h30 text-left left">Passenger(s): <strong><?php echo $result->p2p_bp_npassenger; ?></strong></div>
                        <div class="col-md-4 col-xs-12 h30 text-left left">Luggage(s): <strong><?php echo $result->p2p_bp_nluggage; ?></strong></div>
                        <div class="col-md-12 col-xs-12 h30 text-left left">Service: <strong><?php echo $result->p2p_bp_servicetype; ?></strong></div>
                    </div>
                </div>
                <div class="space5"></div>
                <div class="panel panel-info">
                    <div class="panel-heading"><h3 class="panel-title">Client Information</h3></div>        
                    <div class="panel-body">
                        <div class="col-md-3 col-xs-12 h30 text-left left">Name: <strong><?php echo $result->p2p_bp_first_name.' '.$result->p2p_bp_last_name; ?></strong></div>
                        <div class="col-md-3 col-xs-12 h30 text-left left">Phone: <strong><?php echo $result->p2p_bp_phone; ?></strong></div>
                        <div class="col-md-3 col-xs-12 h30 text-left left">E-mail: <strong><?php echo $result->p2p_bp_email; ?></strong></div>
                        <div class="col-md-3 col-xs-12 h30 text-left left">Origin Ip: <strong><?php echo $result->p2p_bp_ip; ?></strong></div>
                    </div>
                </div>           
                <div class="space5"></div>
                <div class="panel panel-info">
                    <div class="panel-heading"><h3 class="panel-title">Payment Information</h3></div>        
                    <div class="panel-body">
                        <div class="col-md-4 col-xs-12 h30 text-left left">Transaction Id: <strong><?php echo $result->p2p_bp_payment_id; ?></strong></div>
                        <div class="col-md-4 col-xs-12 h30 text-left left">Value: <strong><?php echo get_option('select_currency'); ?> <?php echo $result->p2p_bp_payment_value; ?></strong></div>
                        <div class="col-md-4 col-xs-12 h30 text-left left">Company: <strong><?php echo $result->p2p_bp_payment_company; ?></strong></div>
                    </div>
                </div>
                <div class="space5"></div>
                <div class="w100p carBox left" id="divDone<?php echo $result->p2p_bp_id; ?>" style="display: none;">Trip status changed</div>
                <div class="input-group margin-bottom-sm right buttonDone">
                    <button type="button" class="btn btn-success"  style="display:<?php echo $btTrip['done']?>;" id="done<?php echo $result->p2p_bp_id; ?>" value="<?php echo $result->p2p_bp_id; ?>">Mark as Provided</button>
                    <button type="button" class="btn btn-warning" style="display:<?php echo $btTrip['undone']?>;"  id="undone<?php echo $result->p2p_bp_id; ?>" value="<?php echo $result->p2p_bp_id; ?>">Mark as <strong>NOT</strong> Provided</button>
                </div>   
                <div id="trip<?php echo $result->p2p_bp_id; ?>" class="panel panel-<?php echo $trip;?>">
                    <div class="panel-heading"><h3 class="panel-title">Trip Information</h3></div>        
                    <div class="panel-body">
                        <div class="col-md-6 col-xs-12 text-left left">
                            <div class="panel panel-info">
                                <div class="panel-heading"><h3 class="panel-title">Pick-up Information</h3></div>        
                                <div class="panel-body">
                                    <div class="w100p pTop10 h30">Address: <?php echo $result->p2p_bp_p_address.' '.$aptReservationP; ?></div>
                                    <div class="w100p pTop10 h30">City/State: <?php echo $result->p2p_bp_p_city.'/'.$result->p2p_bp_p_state;  ?></div>
                                    <div class="w100p pTop10 h30">Zip Code: <?php echo $result->p2p_bp_p_zip;  ?></div>
                                    <div class="w100p pTop10 h30">When: <?php echo 'On '.$control->reverseDate($result->p2p_bp_p_date).' at '.$result->p2p_bp_p_time; ?></div>
                                    <div class="w100p pTop10 h30">Instructions: <?php echo $result->p2p_bp_p_instructions; ?></div>
                                </div>
                            </div>                
                        </div>
                        <div class="col-md-6 col-xs-12 text-left left">
                            <div class="panel panel-info">
                                <div class="panel-heading"><h3 class="panel-title">Drop-off Information</h3></div>        
                                <div class="panel-body">
                                    <div class="w100p pTop10 h30">Address: <?php echo $result->p2p_bp_d_address.' '.$aptReservationD; ?></div>
                                    <div class="w100p pTop10 h30">City/State: <?php echo $result->p2p_bp_d_city.'/'.$result->p2p_bp_d_state;  ?></div>
                                    <div class="w100p pTop10 h30">Zip Code: <?php echo $result->p2p_bp_d_zip;  ?></div>
                                </div>
                            </div>                
                        </div>
                    </div>
                </div>          
                <div class="space5"></div>
                <?php if ($result->p2p_bp_r) { ?>
                    <div class="w100p carBox left" id="divRoundDone<?php echo $result->p2p_bp_id; ?>" style="display: none;">Trip status changed</div>
                    <div class="input-group margin-bottom-sm right buttonDone">
                        <button type="button" class="btn btn-success"  style="display:<?php echo $btTrip['done_r']?>;" id="roundDone<?php echo $result->p2p_bp_id; ?>" value="<?php echo $result->p2p_bp_id; ?>">Mark as Provided</button>
                        <button type="button" class="btn btn-warning"  style="display:<?php echo $btTrip['undone_r']?>;"  id="roundUndone<?php echo $result->p2p_bp_id; ?>" value="<?php echo $result->p2p_bp_id; ?>">Mark as <strong>NOT</strong> Provided</button>
                    </div>   
                    <div id="roundtrip<?php echo $result->p2p_bp_id; ?>" class="panel panel-<?php echo $trip_r;?>">
                        <div class="panel-heading"><h3 class="panel-title">Round Trip Information</h3></div>        
                        <div class="panel-body">
                            <div class="col-md-6 col-xs-12 text-left left">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><h3 class="panel-title">Pick-up Information</h3></div>        
                                    <div class="panel-body">
                                        <div class="w100p pTop10 h30">Address: <?php echo $result->p2p_bp_d_address.' '.$aptReservationD; ?></div>
                                        <div class="w100p pTop10 h30">City/State: <?php echo $result->p2p_bp_d_city.'/'.$result->p2p_bp_d_state;  ?></div>
                                        <div class="w100p pTop10 h30">Zip Code: <?php echo $result->p2p_bp_d_zip;  ?></div>
                                        <div class="w100p pTop10 h30">When: <?php echo 'On '.$control->reverseDate($result->p2p_bp_r_p_date).' at '.$result->p2p_bp_r_p_time; ?></div>
                                        <div class="w100p pTop10 h30">Intructions: <?php echo $result->p2p_bp_r_p_instructions; ?></div>
                                    </div>
                                </div>                
                            </div>
                            <div class="col-md-6 col-xs-12 text-left left">
                                <div class="panel panel-info">
                                    <div class="panel-heading"><h3 class="panel-title">Drop-off Information</h3></div>        
                                    <div class="panel-body">
                                        <div class="w100p pTop10 h30">Address: <?php echo $result->p2p_bp_p_address.' '.$aptReservationP; ?></div>
                                        <div class="w100p pTop10 h30">City/State: <?php echo $result->p2p_bp_p_city.'/'.$result->p2p_bp_p_state;  ?></div>
                                        <div class="w100p pTop10 h30">Zip Code: <?php echo $result->p2p_bp_p_zip;  ?></div>
                                    </div>
                                </div>                
                            </div>
                        </div>
                    </div>     
                <?php } ?>
            </div>
        </div>
	</div>
	<div class="space"></div>
	<script>
		jQuery("#delete<?php echo $result->p2p_bp_id; ?>").click(function () {
			jQuery("#divDelete<?php echo $result->p2p_bp_id; ?>").css("display","block");
            jQuery("#delete<?php echo $result->p2p_bp_id; ?>").css("display","none");
		});
		
		jQuery("#close<?php echo $result->p2p_bp_id; ?>").click(function () {
			jQuery("#divDelete<?php echo $result->p2p_bp_id; ?>").css("display","none");
            jQuery("#delete<?php echo $result->p2p_bp_id; ?>").css("display","block");
		});
		
		jQuery("#confirm<?php echo $result->p2p_bp_id; ?>").click(function() {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/reservation_delete.php",
                data: {
                    reservation: 'reservation<?php echo $result->p2p_bp_id; ?>',
                    id: '<?php echo $result->p2p_bp_id; ?>'
                }
            }).done(function( msg ) {
                jQuery("#divDelete<?php echo $result->p2p_bp_id; ?>").html(msg);
            });				
                
            jQuery("#reservation<?php echo $result->p2p_bp_id; ?>").delay(1500).fadeOut(500);
		});
        
        jQuery("#done<?php echo $result->p2p_bp_id; ?>").click(function () {
			jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").css("display","block");
            jQuery("#done<?php echo $result->p2p_bp_id; ?>").css("display","none");
            jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").removeClass("divUndone").addClass("divDone");
            
            jQuery.ajax({
                type: "POST",
                url: "<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/reservation_done.php",
                data: {
                    id: '<?php echo $result->p2p_bp_id; ?>',
                    trip: 0,
                    value: 1
                }
            }).done(function( msg ) {
                jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").html(msg);
            });				        
            
            jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").delay(2500).fadeOut(500);
            jQuery("#undone<?php echo $result->p2p_bp_id; ?>").delay(3000).fadeIn(500);
            jQuery("#trip<?php echo $result->p2p_bp_id; ?>").removeClass("panel-info").removeClass("panel-danger").removeClass("panel-warning").addClass("panel-success");
		});
        
        jQuery("#undone<?php echo $result->p2p_bp_id; ?>").click(function () {
			jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").css("display","block");
            jQuery("#undone<?php echo $result->p2p_bp_id; ?>").css("display","none");
            jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").removeClass("divDone").addClass("divUndone");

            jQuery.ajax({
                type: "POST",
                url: "<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/reservation_done.php",
                data: {
                    id: '<?php echo $result->p2p_bp_id; ?>',
                    trip: 0,
                    value: 0
                }
            }).done(function( msg ) {
                jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").html(msg);
            });				        
            
            jQuery("#divDone<?php echo $result->p2p_bp_id; ?>").delay(2500).fadeOut(500);
            jQuery("#done<?php echo $result->p2p_bp_id; ?>").delay(3000).fadeIn(500);
            jQuery("#trip<?php echo $result->p2p_bp_id; ?>").removeClass("panel-info").removeClass("panel-danger").removeClass("panel-success").addClass("panel-warning");
		});
        
        jQuery("#roundDone<?php echo $result->p2p_bp_id; ?>").click(function () {
			jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").css("display","block");
            jQuery("#roundDone<?php echo $result->p2p_bp_id; ?>").css("display","none");
            jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").removeClass("divUndone").addClass("divDone");
            
            jQuery.ajax({
                type: "POST",
                url: "<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/reservation_done.php",
                data: {
                    id: '<?php echo $result->p2p_bp_id; ?>',
                    trip: 1,
                    value: 1
                }
            }).done(function( msg ) {
                jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").html(msg);
            });				        
            
            jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").delay(2500).fadeOut(500);
            jQuery("#roundUndone<?php echo $result->p2p_bp_id; ?>").delay(3000).fadeIn(500);
            jQuery("#roundtrip<?php echo $result->p2p_bp_id; ?>").removeClass("panel-info").removeClass("panel-danger").removeClass("panel-warning").addClass("panel-success");
		});
        
        jQuery("#roundUndone<?php echo $result->p2p_bp_id; ?>").click(function () {
			jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").css("display","block");
            jQuery("#roundUndone<?php echo $result->p2p_bp_id; ?>").css("display","none");
            jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").removeClass("divDone").addClass("divUndone");

            jQuery.ajax({
                type: "POST",
                url: "<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/reservation_done.php",
                data: {
                    id: '<?php echo $result->p2p_bp_id; ?>',
                    trip: 1,
                    value: 0
                }
            }).done(function( msg ) {
                jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").html(msg);
            });				        
            
            jQuery("#divRoundDone<?php echo $result->p2p_bp_id; ?>").delay(2500).fadeOut(500);
            jQuery("#roundDone<?php echo $result->p2p_bp_id; ?>").delay(3000).fadeIn(500);
            jQuery("#roundtrip<?php echo $result->p2p_bp_id; ?>").removeClass("panel-info").removeClass("panel-danger").removeClass("panel-success").addClass("panel-warning");
		});        
	</script>
<?php
		}
?>
</div>
</div>