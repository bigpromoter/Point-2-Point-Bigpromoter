<?php include_once(P2P_DIR_INCLUDE.'admin.php'); ?>
<?php
    $results = $model_reservation->getReservation((isset($_POST['date_from'])?$_POST['date_from']:NULL), (isset($_POST['date_to'])?$_POST['date_to']:NULL), (isset($_POST['is_provided'])?$_POST['is_provided']:2));
?>
<div id="p2p_bp_dashboard">
    <?php $control_util->settings(isset($_GET['settings-updated'])?$_GET['settings-updated']:NULL); ?>
        <div id="p2p_bp_header" class="w100p">
            <div class="dash_left"><div class="p2p_bp_title_header"><?php echo P2P_TITLE; ?></div></div>
            <div class="dash_right">
                <div class="left">
                    <div class='p2p_bp_header-text p2p_bp_tab' style='display:block;'><i class='fa fa-file-text-o'></i> <?php _e('Manage Reservation', P2P_TRANSLATE); ?></div>
                </div>
                <div class="right"></div>
            </div>
        </div>
        <div id="p2p_bp_body" class="left ">
            <div id="p2p_bp_loading" class="p2p_bp_tab"><img src="<?php echo P2P_DIR_IMAGES; ?>ajax-loader-2.gif" /></div>
            <div id="p2p_bp_reservation" class="p2p_bp_tab"  style="display:none;">
                <div class="reservationSearch">
                    <form method="POST" action="?page=p2p_bigpromoter_reservation">
                        <div class="w100p left">
                            <div class="w48p left">
                                <span>From</span>
                                <input class="hasDatepicker" type="text" name="date_from" id="date_from" value="<?php echo (isset($_POST['date_from']))?$_POST['date_from']:'';?>">
                                <span>To</span>
                                <input class="hasDatepicker" type="text" name="date_to" id="date_to" value="<?php echo (isset($_POST['date_to']))?$_POST['date_to']:'';?>">
                            </div>
                            <script>
                                jQuery(function() {
                                    jQuery("#date_from").datepicker({defaultDate: "-1d", changeMonth: true, changeYear: true});
                                    jQuery("#date_to").datepicker({defaultDate: "+1d", changeMonth: true, changeYear: true});
                                });
                            </script>                    
                            <div style="width:100%; height: 5px;"></div>
                            <?php
                                $checked_all = ((isset($_POST['is_provided'])) && ($_POST['is_provided'] == 2))?'checked':'';
                                $checked_provided = ((isset($_POST['is_provided'])) && ($_POST['is_provided'] == 1))?'checked':'';
                                $checked_not_provided = ((isset($_POST['is_provided'])) && ($_POST['is_provided'] == 0))?'checked':'';
                                $checked_all = (!isset($_POST['is_provided']))?'checked':$checked_all;
                            ?>
                            <div class="w48p right">
                                <div class="p2p_bp_radio">
                                    <input type="radio" name="is_provided" id="all" value='2' <?php echo $checked_all; ?>> 
                                        <label for="all"><?php _e('All', P2P_TRANSLATE); ?></label>
                                    
                                    <input type="radio" name="is_provided"  id="provided" value='1' <?php echo $checked_provided; ?>>
                                        <label for="provided"><?php _e('Provided', P2P_TRANSLATE); ?></label>
                                    
                                    <input type="radio" name="is_provided" id="notprovided" value='0' <?php echo $checked_not_provided; ?>> 
                                        <label for="notprovided"><?php _e('Not Provided', P2P_TRANSLATE); ?></label>
                                </div>
                            </div>
                        </div>
                        <div style="width:100%; height: 5px;"></div>
                        <button type="submit" name="submit" id="submit" class="p2p_bp_save right"><?php _e('Search', P2P_TRANSLATE); ?></button>
                    </form>
                </div>
                <div class="reservationTop left w100p">
                    <div class="reservationSeeMore"></div>
                    <div class="reservationId"><?php _e('Id', P2P_TRANSLATE); ?></div>
                    <div class="reservationDate"><?php _e('Data', P2P_TRANSLATE); ?></div>
                    <div class="reservationClient"><?php _e('Client', P2P_TRANSLATE); ?></div>
                    <div class="reservationFrom"><?php _e('From', P2P_TRANSLATE); ?></div>
                    <div class="reservationTo"><?php _e('To', P2P_TRANSLATE); ?></div>
                    <div class="reservationProvided"><?php _e('Provided', P2P_TRANSLATE); ?></div>
                    <div class="reservationDelete"></div>
                </div>
            <?php
                foreach ($results as $result) {
            ?>
                <div id="showAjax<?php echo $result->p2p_bp_id; ?>" class="p2p_bp_alert left p2p_bp_hide w100p"></div>
                <div id="table<?php echo $result->p2p_bp_id; ?>" class="reservationTable left w100p">
                    <div class="reservationSeeMore"><div id="<?php echo $result->p2p_bp_id; ?>" class="p2p_reservation_delete p2p_bp_btn p2p_bp_btn_remove" ><i class="fa fa-remove"></i></div></div>
                    <div class="reservationId"><?php echo $result->p2p_bp_id; ?></div>
                    <div class="reservationDate"><?php echo $control_util->changeDate($result->p2p_bp_p_date, true); ?></div>
                    <div class="reservationClient"><?php echo $result->p2p_bp_first_name.' '.$result->p2p_bp_last_name; ?><BR><?php echo $result->p2p_bp_phone; ?></div>
                    <div class="reservationFrom"><?php echo $result->p2p_bp_p_apt.' '.$result->p2p_bp_p_address; ?><BR><?php echo $result->p2p_bp_p_city.'/'.$result->p2p_bp_p_state; ?></div>
                    <div class="reservationTo"><?php echo $result->p2p_bp_d_apt.' '.$result->p2p_bp_d_address; ?><BR><?php echo $result->p2p_bp_d_city.'/'.$result->p2p_bp_d_state; ?></div>
                    <div class="reservationProvided">
                        <?php if($result->p2p_bp_done) { ?>
                            <div class="p2p_bp_ball p2p_bp_ball_green left"></div>
                        <?php } else { ?>
                            <div class="p2p_bp_ball p2p_bp_ball_red left"></div>
                        <?php } ?>
                        <?php if($result->p2p_bp_r) { ?>
                            <?php if($result->p2p_bp_done_r) { ?>
                                <div class="p2p_bp_ball p2p_bp_ball_green right "></div>
                            <?php } else { ?>
                                <div class="p2p_bp_ball p2p_bp_ball_red right"></div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="reservationDelete">
                        <div id="<?php echo $result->p2p_bp_id; ?>" class="p2p_reservation_btn_seemore<?php echo $result->p2p_bp_id; ?> p2p_reservation_seemore p2p_bp_btn p2p_bp_btn_add p2p_bp_show right" ><i class="fa fa-eye"></i></div>
                        <div id="<?php echo $result->p2p_bp_id; ?>" class="p2p_reservation_btn_seemore_close<?php echo $result->p2p_bp_id; ?> p2p_reservation_seemore_close p2p_bp_btn p2p_bp_btn_add p2p_bp_hide right" ><i class="fa fa-minus"></i></div>
                    </div>
                </div>
                <div id="showSeeMore<?php echo $result->p2p_bp_id; ?>" class="left p2p_bp_hide w100p">Loading...</div>
            <?php
                }
            ?>
            </div>
        </div>
        <div id="p2p_bp_footer" class="left w100p">
            <div class="dash_right">
                <div class="right"></div>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(function () {
    reservation();
});
</script>