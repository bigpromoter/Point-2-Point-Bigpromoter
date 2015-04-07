<?php include_once(P2P_DIR_INCLUDE.'admin.php'); ?>
<?php
    //Use WP Media handler
    wp_enqueue_media();
    
    //Load Script/Style to use Color Picker on Custom Style
    wp_enqueue_script('custom-background');
    wp_enqueue_style('wp-color-picker');
    
    //Options
    $option = get_option('p2p_bp');
?>
<div id="p2p_bp_dashboard">
    <form id="p2p_bp" method="post" action="options.php">
        <?php settings_fields( 'p2p_bigpromoter_fleet' );?>
        <?php do_settings_sections( 'p2p_bigpromoter_fleet' );?>
        <?php $control_util->settings(isset($_GET['settings-updated'])?$_GET['settings-updated']:NULL); ?>
        <div id="p2p_bp_header" class="w100p">
            <div class="dash_left"><div class="p2p_bp_title_header"><?php echo P2P_TITLE; ?></div></div>
            <div class="dash_right">
                <div class="left">
                    <div id='p2p_bp_icon_wrench' class='p2p_bp_header-text p2p_bp_tab' style='display:block;'><i class='fa fa-automobile'></i> <?php _e('Manage Fleet', P2P_TRANSLATE); ?></div>
                </div>
                <div class="right"><button type="submit" name="submit" id="submit" class="p2p_bp_save right"><?php _e('Save Changes', P2P_TRANSLATE); ?></button></div>
            </div>
        </div>
        <div id="p2p_bp_body" class="left w100p">
            <div id="p2p_bp_loading" class="p2p_bp_tab"><img src="<?php echo P2P_DIR_IMAGES; ?>ajax-loader-2.gif" /></div>
            <div id="p2p_bp_fleet"class="p2p_bp_tab" style="display: none;">
                <div id="fleetInfo"></div>
                <div class="carTable carTop left w100p">
                    <div class="colAction"><?php _e('Action', P2P_TRANSLATE); ?></div>
                    <div class="colBody"><?php _e('Body', P2P_TRANSLATE); ?></div>
                    <div class="colLess">< <span id="span_distance"><?php echo $option['basic']['distance']; ?></span> <span id="span_select_distance"><?php echo $option['basic']['select_distance']; ?></span></div>
                    <div class="colMore">> <span id="span_distance"><?php echo $option['basic']['distance']; ?></span> <span id="span_select_distance"><?php echo $option['basic']['select_distance']; ?></span></div>
                    <div class="colMin"><?php _e('Minimum', P2P_TRANSLATE); ?></div>
                    <div class="colPassenger"><?php _e('Pass.', P2P_TRANSLATE); ?></div>
                    <div class="colLuggage"><?php _e('Lugg.', P2P_TRANSLATE); ?></div>
                    <div class="colColor"><?php _e('Color', P2P_TRANSLATE); ?></div>
                    <div class="colImage"><?php _e('Image', P2P_TRANSLATE); ?></div>
                </div>
            <?php
                $fleet = get_option('p2p_bp_fleet');
                if (isset($fleet['body']) && is_array($fleet['body']) && count($fleet['body']) > 0) {
                    for ($i = 0; $i < count($fleet['body']); $i++) {
            ?>
            
                <div id="showAjax<?php echo $i; ?>" class="p2p_bp_alert left p2p_bp_hide w90p"></div>
                <div id="table<?php echo $i; ?>" class="carTable left w100p">
                    <div class="colAction">
                        <div id="p2p_bp_car_delete_btn"><div id="<?php echo $i; ?>" class="p2p_fleet_delete p2p_bp_btn p2p_bp_btn_remove" ><i class="fa fa-remove"></i></div></div>
                    </div>
                    <div class="colBody">
                        <input type="text" id="<?php echo $i; ?>" name="p2p_bp_fleet[body][]" value="<?php echo $fleet['body'][$i]; ?>" class="w90p fleet_body" placeholder="<?php _e('Body', P2P_TRANSLATE); ?>"/>
                    </div>
                    <div class="junNum">
                        <div class="colLess">
                            <span class="descLess">< <span id="span_distance"><?php echo $option['basic']['distance']; ?> <?php echo $option['basic']['select_distance']; ?></span><BR></span>
                            <span class="currency w30p"><?php echo $option['basic']['select_currency']; ?></span>
                            <input type="text" name="p2p_bp_fleet[lessThan][]" id="<?php echo $i; ?>" value="<?php echo $fleet['lessThan'][$i]; ?>"  class="w70p fleet_lessThan"/>
                        </div>
                        <div class="colMore">
                            <span class="descLess">< <span id="span_distance"><?php echo $option['basic']['distance']; ?> <?php echo $option['basic']['select_distance']; ?></span><BR></span>
                            <span class="currency w30p"><?php echo $option['basic']['select_currency']; ?></span>
                            <input type="text" name="p2p_bp_fleet[moreThan][]" id="<?php echo $i; ?>" value="<?php echo $fleet['moreThan'][$i]; ?>"  class="w70p fleet_moreThan"/>
                        </div>
                        <div class="colMin">
                          <span class="descLess"><?php _e('Minimum', P2P_TRANSLATE); ?><BR></span>
                          <span class="currency w30p"><?php echo $option['basic']['select_currency']; ?></span>
                          <input type="text" name="p2p_bp_fleet[minimum][]" id="<?php echo $i; ?>" value="<?php echo $fleet['minimum'][$i]; ?>" class="w70p fleet_minimum" placeholder="Min"/>
                        </div>
                    </div>
                    <div class="junNum">
                        <div class="colPassenger">
                            <span class="descLess"><?php _e('Passenger', P2P_TRANSLATE); ?><BR></span>
                            <select id="<?php echo $i; ?>" name="p2p_bp_fleet[nPass][]" class="fleet_nPass">
                            <?php for ($n = 1; $n <= P2P_MAX_PASSENGER; $n++) {
                                  $select = ($fleet['nPass'][$i] == $n)?'selected':'';
                            ?>
                                <option value="<?php echo $n; ?>" <?php echo $select; ?>><?php echo $n; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="colLuggage">
                            <span class="descLess"><?php _e('Luggage', P2P_TRANSLATE); ?><BR></span>
                            <select id="<?php echo $i; ?>" name="p2p_bp_fleet[nLugg][]" class="fleet_nPass">
                            <?php for ($n = 1; $n <= P2P_MAX_LUGGAGE; $n++) {
                                  $select = ($fleet['nLugg'][$i] == $n)?'selected':'';
                            ?>
                                <option value="<?php echo $n; ?>" <?php echo $select; ?>><?php echo $n; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="colColor">
                            <span class="descLess">Color<BR></span>
                            <select id="<?php echo $i; ?>" name="p2p_bp_fleet[color][]" class="fleet_nPass">
                                <option value="1" <?php echo ($fleet['color'][$i] == '1')?'selected':''; ?>>1</option>
                                <option value="2" <?php echo ($fleet['color'][$i] == '2')?'selected':''; ?>>2</option>
                                <option value="3" <?php echo ($fleet['color'][$i] == '3')?'selected':''; ?>>3</option>
                                <option value="4" <?php echo ($fleet['color'][$i] == '4')?'selected':''; ?>>4</option>
                                <option value="5" <?php echo ($fleet['color'][$i] == '5')?'selected':''; ?>>5</option>
                                <option value="6" <?php echo ($fleet['color'][$i] == '6')?'selected':''; ?>>6</option>
                                <option value="7" <?php echo ($fleet['color'][$i] == '7')?'selected':''; ?>>7</option>
                                <option value="8" <?php echo ($fleet['color'][$i] == '8')?'selected':''; ?>>8</option>
                                <option value="9" <?php echo ($fleet['color'][$i] == '9')?'selected':''; ?>>9</option>
                                <option value="10" <?php echo ($fleet['color'][$i] == '10')?'selected':''; ?>>10</option>
                                <option value="11" <?php echo ($fleet['color'][$i] == '11')?'selected':''; ?>>11</option>
                            </select>
                        </div>
                    </div>
                    <div class="colImage">
                        <div id="thumb<?php echo $i; ?>" class="thumbCar"><img src="<?php echo $fleet['pic'][$i]; ?>" class="thumbCar"/></div>
                        <input id="pic<?php echo $i; ?>" type="hidden" class="w90p fleet_pic" name="p2p_bp_fleet[pic][]" value="<?php echo $fleet['pic'][$i]; ?>" />
                        <button id="<?php echo $i; ?>" name="upload_picNew" class="onlyMarginLeft15 p2p_bp_save fleet_upload_pic" type="button"><?php _e('Upload Image', P2P_TRANSLATE); ?></button>
                    </div>
                </div>
            
            <?php
                    }
                }
            ?>
                
                
                
                <div id="newCar"></div>
                <div id="showAjax1000" class="p2p_bp_alert left p2p_bp_hide w90p"></div>   
                <div class="carTable carBottom left w100p">
                    <div class="colAction">
                        <div id="p2p_bp_car_add_btn"><div id="1000" class="p2p_fleet_add p2p_bp_btn p2p_bp_btn_add" ><i class="fa fa-plus"></i></div></div>
                    </div>
                    <div class="colBody">
                        <input type="text" id="bodytypeNew" value="" class='w90p' placeholder="<?php _e('Body', P2P_TRANSLATE); ?>"/>
                    </div>
                    <div class="junNum">
                        <div class="colLess">
                            <span class="descLess">< <span id="span_distance"><?php echo $option['basic']['distance']; ?> <?php echo $option['basic']['select_distance']; ?></span><BR></span>
                            <span class="currency w30p"><?php echo $option['basic']['select_currency']; ?></span>
                            <input type="text" name="less_thanNew" id="less_thanNew" value=""  class="w70p"/>
                        </div>
                        <div class="colMore">
                            <span class="descLess">< <span id="span_distance"><?php echo $option['basic']['distance']; ?> <?php echo $option['basic']['select_distance']; ?></span><BR></span>
                            <span class="currency w30p"><?php echo $option['basic']['select_currency']; ?></span>
                            <input type="text" name="more_thanNew" id="more_thanNew" value=""  class="w70p"/>
                        </div>
                        <div class="colMin">
                          <span class="descLess"><?php _e('Minimum', P2P_TRANSLATE); ?><BR></span>
                          <span class="currency w30p"><?php echo $option['basic']['select_currency']; ?></span>
                          <input type="text" id="minNew" value="" class='w70p' placeholder="Min"/>
                        </div>
                    </div>
                    <div class="junNum">
                        <div class="colPassenger">
                            <span class="descLess"><?php _e('Passenger', P2P_TRANSLATE); ?><BR></span>
                            <select id="nPassNew" name="nPassNew">
                            <?php for ($n = 1; $n <= P2P_MAX_PASSENGER; $n++) { ?>
                                <option value="<?php echo $n; ?>"><?php echo $n; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="colLuggage">
                            <span class="descLess"><?php _e('Luggage', P2P_TRANSLATE); ?><BR></span>
                            <select id="nLuggNew" name="nLuggNew">
                            <?php for ($n = 1; $n <= P2P_MAX_LUGGAGE; $n++) { ?>
                                <option value="<?php echo $n; ?>"><?php echo $n; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="colColor">
                            <span class="descLess"><?php _e('Color', P2P_TRANSLATE); ?><BR></span>
                            <select id="colorNew" name="colorNew">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                            </select>
                        </div>
                    </div>
                    <div class="colImage">
                        <div id="picNewThumb" class="thumbCar"></div>
                        <input id="picNew" type="hidden" class="w90p" name="picNew" value="" /> 
                        <button id="upload_picNew" class="onlyMarginLeft15 p2p_bp_save" type="button"><?php _e('Upload Image', P2P_TRANSLATE); ?></button>
                    </div>
                </div>
                <div class="p2p_option">
                    <div class="carTable carTop left w100p">
                        <div class="colBody w100p"><?php _e('Fleet Options', P2P_TRANSLATE); ?></div>
                    </div>
                    <div class="carTable left w100p">
                        <div class="w100p">
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php _e('Increase per ride:',P2P_TRANSLATE); ?></th>
                                    <td colspan="3"><?php echo $option['basic']['select_currency'].$control_form->createInput("p2p_bp_fleet_increase_ride", get_option('p2p_bp_fleet_increase_ride'), ""); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="p2p_bp_footer" class="left w100p">
                <div class="dash_right">
                    <div class="right"><button type="submit" name="submit" id="submit" class="p2p_bp_save right"><?php _e('Save Changes', P2P_TRANSLATE); ?></button></div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
jQuery(function () {
    fleet();
});
/*
1 - A4BDFC
2 - 7AE7BF
3 - DBADFF
4 - FF887C
5 - FBD75B
6 - FFB878
7 - 46D6DB
8 - E1E1E1
9 - 5484ED
10 - 51B749
11 - DC2127
*/
</script>
