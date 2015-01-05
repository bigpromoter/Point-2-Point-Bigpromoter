<div class="wrap">
<?php
    $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
    $control->checkUpdate($uptaded);
?>
    <form method="post" action="options.php">
        <div class="panel panel-primary">
             <div class="panel-heading">
                     <h3 class="panel-title">Calendar Settings</h3>
             </div>
             <div class="panel-body">
                <?php settings_fields( 'p2p_bigpromoter_calendar' );?>
                <?php do_settings_sections( 'p2p_bigpromoter_calendar' );?>
                <table class="form-table">      
                    <tr valign="top">
                        <th scope="row">Use Google Calendar:</th>
                        <td colspan="3">
                            <div class="btn-group" data-toggle="buttons">
                                <?php $calendarActive = $control->borderColor(get_option('p2p_calendar_enabled'),'calendar'); ?>
                                <label id="calendar-yes" class="btn btn-primary <?php echo $calendarActive['active_yes_calendar']; ?>">
                                    <input type="radio" name="p2p_calendar_enabled" id="calendar_yes" value='1' <?php echo (isset($calendarActive['checked_yes_calendar']))?$calendarActive['checked_yes_calendar']:''; ?>> Yes
                                </label>
                                <label id="calendar-no" class="btn btn-primary <?php echo $calendarActive['active_no_calendar']; ?>">
                                    <input type="radio" name="p2p_calendar_enabled"  id="calendar_no" value='0' <?php echo (isset($calendarActive['checked_no_calendar']))?$calendarActive['checked_no_calendar']:'';; ?>> No
                                </label>
                            </div>
                            <script>
                                jQuery('#calendar-yes').click(function() {
                                    jQuery('#calendar_active').css('display','block');
                                });
                                jQuery('#calendar-no').click(function() {
                                    jQuery('#calendar_active').css('display','none');
                                });
                            </script>
                        </td>
                    </tr>
                </table>
                
                <div id="calendar_active" style="display: <?php echo $calendarActive['display_calendar']; ?>">
                    <div class="alert alert-info" role="alert">Google Calendar</div>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Key file name</th>
                            <td colspan="3"><input type="text" name="p2p_calendar_keyfilename" value="<?php echo get_option('p2p_calendar_keyfilename'); ?>" class="w100p" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Service account email address</th>
                            <td colspan="3"><input type="text" name="p2p_calendar_serviceemail" value="<?php echo get_option('p2p_calendar_serviceemail'); ?>"  class="w100p"/></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Calendar Id</th>
                            <td colspan="3"><input type="text" name="p2p_calendar_used" value="<?php echo get_option('p2p_calendar_used'); ?>" class="w100p"/></td>
                        </tr>
                        <tr valign="top">
  <?php                      
// $current_offset = get_option('gmt_offset');
// $tzstring = get_option('timezone_string');

?>
                            <th scope="row">Time Zone</th>
                            <td colspan="3"><?php echo get_option('timezone_string').' (GMT Offset: '.get_option('gmt_offset').')'; ?> / This plugin use the WordPress Time-zone (<a href="options-general.php" target="_blank">Click here do change</a>)</td>
                        </tr>
                        <?php
                        if (get_option('p2p_calendar_keyfilename') &&
                            get_option('p2p_calendar_serviceemail') &&
                            get_option('p2p_calendar_used')
                        ) {
                        ?>
                        <tr valign="top">
                            <th scope="row">Test Data:</th>
                            <td colspan="3"><div id="calendarTest" class="w100p btn btn-std bgGray">Test Settings</div></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                    <script>
                    //Manage Add Car	
                        jQuery("#calendarTest").click(function () {
                            ajaxTestCalendar("<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/calendar_test.php","#divCalendarTest");
                        });
                    </script>
                    <div id="divCalendarTest" style="display:none; min-height: 52px;">
                        <div id="apiStatus" class="alert alert-info">Please wait <strong><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/style/images/ajax-loader.gif" /> Loading...</strong></div>
                    </div>                     
                    <div class="alert alert-info" role="alert">Calendar Info</div>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row" class="w20p calendarTable">Title</th>
                            <td colspan="2" class="w60p calendarTable"><input type="text" id="p2p_calendar_title" name="p2p_calendar_title" value="<?php echo get_option('p2p_calendar_title'); ?>" class="w100p" /></td>
                            <td scope="row" class="w20p calendarTable">
                                <?php echo $control->valuesCalendar('p2p_calendar_value_title','p2p_calendar_value_title_insert','p2p_calendar_title'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row" class="w20p calendarTable">Description: Client Info</th>
                            <td colspan="2" class="w60p calendarTable"><textarea id="p2p_calendar_description_client" name="p2p_calendar_description_client" class="w100p" rows="5"><?php echo get_option('p2p_calendar_description_client'); ?></textarea></td>
                            <td scope="row" class="w20p calendarTable">
                                <?php echo $control->valuesCalendar('p2p_calendar_value_client','p2p_calendar_value_client_insert','p2p_calendar_description_client'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row" class="w20p calendarTable">Description: Location Info</th>
                            <td colspan="2" class="w60p calendarTable"><textarea id="p2p_calendar_description_location" name="p2p_calendar_description_location" class="w100p" rows="5"><?php echo get_option('p2p_calendar_description_location'); ?></textarea></td>
                            <td scope="row" class="w20p calendarTable">
                                <?php echo $control->valuesCalendar('p2p_calendar_value_location','p2p_calendar_value_location_insert','p2p_calendar_description_location'); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    <?php submit_button(); ?>
    </form>
</div>