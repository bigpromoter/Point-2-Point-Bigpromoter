<?php
include_once (dirname(__FILE__)."/../include.php");

//Enqueue Media to be user on Manage Fleet
wp_enqueue_media();

$results = $model->getResults($model->fleet['table'], 'ORDER BY '.$model->fleet['prefix'].'name');
?>
	<div class="wrap">
		<h2>Point to Point - Big Promoter</h2>	
        <div class="p2p_bp_panel p2p_bp_panel-primary   pull-left w100p ">
            <div class="p2p_bp_panel-heading">
                    <h3 class="p2p_bp_panel-title">Manage Fleet</h3>
            </div>
            <div class="p2p_bp_panel-body">
                <div id="fleetInfo"></div>
                <script>
                    jQuery.ajax({
                    type: "POST",
                    url: 'http://www.bigpromoter.com/API/bigpromoter.php',
                    data: {
                        check: 'fleetInfo',
                        api: '<?php echo get_option('p2p_bigpromoter_api'); ?>',
                        site: '<?php echo get_option('p2p_bigpromoter_site'); ?>'
                    }
                    }).done(function(msg) {
                        jQuery("#fleetInfo").html(msg);
                    });
                </script>
                <div class="carTable carTop left w100p">
                    <div class="colAction">Action</div>
                    <div class="colActive">Active</div>
                    <div class="colBody">Body</div>
                    <div class="colLess">< <span id="span_distance"><?php echo get_option('distance')?></span> <span id="span_select_distance"><?php echo get_option('select_distance'); ?></span></div>
                    <div class="colMore">> <span id="span_distance"><?php echo get_option('distance')?></span> <span id="span_select_distance"><?php echo get_option('select_distance'); ?></span></div>
                    <div class="colMin">Minimum</div>
                    <div class="colPassenger">Passenger</div>
                    <div class="colLuggage">Luggage</div>
                    <div class="colColor">Color</div>
                    <div class="colImage">Image</div>
                </div>
<?php 
			$row = 0;
			foreach ($results as $result) {
				$row++;
				($row % 2 == 0)?$classbg='rowOdd':$classbg='rowEven';
				$i = $result->p2p_bp_cars_id;
?>
                <div id="showAjax<?php echo $i; ?>" class="left w100p" style="background-color: #FFF;"></div>
                
                <div id="table<?php echo $i; ?>" class="carTable <?php echo $classbg; ?> w100p" style="float:left">
                    <div class="colAction">
                        <div id="p2p_car_edit<?php echo $i; ?>" class="button" style="width:27px; padding: 0;"><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/images/icon_edit.png" alt="Edit this Car"/></div>
                        <div id="p2p_car_delete<?php echo $i; ?>" class="button" style="width:27px; padding: 0;"><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/images/icon_delete.png" alt="Delete this Car"/></div>
                    </div>
                    <div class="colActive">
                    <?php ($result->p2p_bp_cars_enabled)?$checked="checked":$checked=""; ?>
                        <input type="checkbox" name="active<?php echo $i; ?>" id="active<?php echo $i; ?>" <?php echo $checked; ?> value="1" />
                    </div>
                    <div class="colBody">
                        <input type="text" id="body<?php echo $i; ?>" value="<?php echo $result->p2p_bp_cars_name; ?>" class='w90p' placeholder="Body"/>
                    </div>
                    <div class="junNum">
                        <div class="colLess">
                            <span class="descLess">< <span id="span_distance"><?php echo get_option('distance')?> <?php echo get_option('select_distance'); ?></span><BR></span>
                            <span class="currency w30p"><?php echo get_option('select_currency'); ?></span>
                            <input type="text" name="less_than<?php echo $i; ?>" id="less_than<?php echo $i; ?>" value="<?php echo $result->p2p_bp_cars_value_lower;  ?>"  class="w70p"/>
                        </div>
                        <div class="colMore">
                            <span class="descLess">> <span id="span_distance"><?php echo get_option('distance')?> <?php echo get_option('select_distance'); ?></span><BR></span>
                            <span class="currency w30p"><?php echo get_option('select_currency'); ?></span>
                            <input type="text" name="more_than<?php echo $i; ?>" id="more_than<?php echo $i; ?>" value="<?php echo $result->p2p_bp_cars_value_higher;  ?>"  class="w70p"/>
                        </div>
                        <div class="colMin">
                          <span class="descLess">Minimum<BR></span>
                          <span class="currency w30p"><?php echo get_option('select_currency'); ?></span>
                          <input type="text" id="min<?php echo $i; ?>" value="<?php echo $result->p2p_bp_cars_min; ?>" class='w70p' placeholder="Min"/>
                        </div>
                    </div>
                    <div class="junNum">                        
                        <div class="colPassenger">
                            <span class="descLess">Passenger<BR></span>
                            <?php echo $control->createSelectNum('nPass'.$i, 'cPass'.$i, 'tPass'.$i, $result->p2p_bp_cars_passenger); ?>
                        </div>
                        <div class="colLuggage">
                            <span class="descLess">Luggage<BR></span>
                            <?php echo $control->createSelectNum('nLugg'.$i, 'cLugg'.$i, 'tLugg'.$i, $result->p2p_bp_cars_luggage); ?>
                        </div>
                        <div class="colColor">
                            <span class="descLess">Color<BR></span>
                            <?php echo $control->createSelectColor('color'.$i, 'cColor'.$i, 'tColor'.$i, $result->p2p_bp_cars_color); ?>
                        </div>
                    </div>
                    <div class="colImage">
                        <div id="pic<?php echo $i; ?>Thumb" class="thumbCar">
                            <img src="<?php echo $result->p2p_bp_cars_pic; ?>" class="thumbCar" />						
                        </div>
                        <input id="pic<?php echo $i; ?>" type="hidden" class="w90p" name="picNew" value="<?php echo $result->p2p_bp_cars_pic; ?>" /> 
                        <input id="upload_pic<?php echo $i; ?>" class="button" type="button" value="Upload Image" />
                    </div>
                </div>
                <script>
                //Manage upload
                    jQuery('#upload_pic<?php echo $i; ?>').click(function(e) {
                        uploadMedia (e, '#pic<?php echo $i; ?>','#pic<?php echo $i; ?>Thumb');
                    });

                    //Check if car is enabled
                    var check<?php echo $i; ?> = <?php echo $result->p2p_bp_cars_enabled; ?>;
                    jQuery("#active<?php echo $i; ?>").click(function () {
                        if (jQuery("#active<?php echo $i; ?>").prop('checked')) {check<?php echo $i; ?> = '1';}
                        else {check<?php echo $i; ?> = '0';}
                    });
                
                //Confirm car edit    
                    jQuery("#p2p_car_edit<?php echo $i; ?>").click(function () {
                        ajaxManageFleet(<?php echo $i; ?>,"<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/fleet_edit_car.php","#showAjax<?php echo $i; ?>",check<?php echo $i; ?>);
                    });
                
                //Confirm car deletion
                    jQuery("#p2p_car_delete<?php echo $i; ?>").click(function () {
                        ajaxManageFleet(<?php echo $i; ?>,"<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/fleet_delete_car.php","#showAjax<?php echo $i; ?>",check<?php echo $i; ?>);            
                    });
                </script>
<?php
			}
?>
            <div id="showAjaxAdd" style="width:100%; background-color: #FFF;"></div>    
            <div class="carTable carBottom left w100p">
                <div class="colAction">
                    <div id="p2p_car_add" class="button" style="width:27px; padding: 0;"><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/images/icon_add.png" alt="Add New Car"/></div>
                </div>
                <div class="colActive">
                    <input type="checkbox" name="activeNew" id="activeNew" checked value="1" />
                </div>
                <div class="colBody">
                    <input type="text" id="bodytypeNew" value="" class='w90p' placeholder="Body"/>
                </div>
                <div class="junNum">
                    <div class="colLess">
                        <span class="descLess">< <span id="span_distance"><?php echo get_option('distance')?> <?php echo get_option('select_distance'); ?></span><BR></span>
                        <span class="currency w30p"><?php echo get_option('select_currency'); ?></span>
                        <input type="text" name="less_thanNew" id="less_thanNew" value=""  class="w70p"/>
                    </div>
                    <div class="colMore">
                        <span class="descLess">< <span id="span_distance"><?php echo get_option('distance')?> <?php echo get_option('select_distance'); ?></span><BR></span>
                        <span class="currency w30p"><?php echo get_option('select_currency'); ?></span>
                        <input type="text" name="more_thanNew" id="more_thanNew" value=""  class="w70p"/>
                    </div>
                    <div class="colMin">
                      <span class="descLess">Minimum<BR></span>
                      <span class="currency w30p"><?php echo get_option('select_currency'); ?></span>
                      <input type="text" id="minNew" value="" class='w70p' placeholder="Min"/>
                    </div>
                    </div>
                    <div class="junNum"> 
                    <div class="colPassenger">
                        <span class="descLess">Passenger<BR></span>
                        <?php echo $control->createSelectNum('nPassNew', 'cPassNew', 'tPassNew', 1); ?>        
                    </div>
                    <div class="colLuggage">
                        <span class="descLess">Luggage<BR></span>
                        <?php echo $control->createSelectNum('nLuggNew', 'cLuggNew', 'tLuggNew', 1); ?>    
                    </div>
                    <div class="colColor">
                        <span class="descLess">Color<BR></span>
                        <?php echo $control->createSelectColor('colorNew', 'cColorNew', 'tColorNew', 1); ?>
                    </div>
                </div>
                <div class="colImage">
                    <div id="picNewThumb" class="thumbCar"></div>
                    <input id="picNew" type="hidden" class="w90p" name="picNew" value="" /> 
                    <input id="upload_picNew" class="button" type="button" value="Upload Image" />
                </div>
            </div>     
                    
            <script>
            //Manage upload
                jQuery('#upload_picNew').click(function(e) {
                    uploadMedia (e, '#picNew','#picNewThumb');
                });
            //Manage upload
                jQuery('#upload_picNew1').click(function(e) {
                    uploadMedia (e, '#picNew','#picNewThumb');
                });
            //Manage Add Car	
                jQuery("#p2p_car_add").click(function () {
                    check = '1';
                    if (jQuery("#activeNew").prop('checked')) {check = '1';}
                    else {check = '0';}
                    
                    ajaxAddFleet("<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/fleet_add_car.php","#showAjaxAdd",check);
                });
            </script>
        </div>
    </div>
</div>
<form method="post" action="options.php">
    <div class="p2p_bp_panel p2p_bp_panel-primary   pull-left w100p">
        <div class="p2p_bp_panel-heading">
                <h3 class="p2p_bp_panel-title">Fleet Options</h3>
        </div>
        <div class="p2p_bp_panel-body">
            <?php		
                settings_fields( 'p2p_bigpromoter_fleet' );
                do_settings_sections( 'p2p_bigpromoter_fleet' );
            ?>
        	<table class="form-table">
                <tr valign="top">
                    <th scope="row">Increase per ride:</th>
                    <td colspan="3"><?php echo get_option('select_currency'); ?> <input type="text" name="increase_ride" value="<?php echo get_option('increase_ride'); ?>"  class="w50p"/></td>
                </tr>
            </table>
        </div>
    </div>
    <?php submit_button(); ?>
</form>