<?php
    //Include Core files from WP
    include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
    include_once ('../../include.php');
    include_once ('../../../system/calendar/calendar.php');

    $insertCalendar = new GoogleCalendar();
    $resultCalendar = $insertCalendar->insert_event('', '', '', 1);
    if ( !current_time('timestamp') ) $tdif = 0;
    else $tdif = current_time('timestamp') - time();
    if ($resultCalendar) {
?>
    <div class="alert p2p_bp_alert-success" role="alert">
        If you didn't get any error, it's time to check your Google Calendar. You should see a new event rigth now!<BR>
        Test made on <?php echo date('m/d/Y h:i:s A', time() + $tdif); ?>
    </div>
<?php
    } else {
?>
    <div class="alert p2p_bp_alert-danger" role="alert">
        Something went wrong! Check the settings above, save and try again!<BR>
        Test made on <?php echo date('m/d/Y h:i:s A', time() + $tdif); ?>
    </div>

<?php } ?>