<?php
include_once (dirname(__FILE__)."/../include.php");	
$results = $model->getResults($model->service['table'], 'ORDER BY '.$model->service['prefix'].'name');
?>
	<div class="wrap">
		<h2>Point to Point - Big Promoter</h2>
        <?php
            $uptaded = (isset($_GET['settings-updated']))?$_GET['settings-updated']:NULL;
            $control->checkUpdate($uptaded);
        ?>
        <div class="p2p_bp_panel p2p_bp_panel-primary pull-left w100p">
            <div class="p2p_bp_panel-heading">
                    <h3 class="p2p_bp_panel-title">Manage Service</h3>
            </div>
            <div class="p2p_bp_panel-body">
                <div class="serviceTable serviceTop">
                    <div class="colIdS">Id</div>
                    <div class="colActionS">Action</div>
                    <div class="colServiceS">Service</div>
                </div>
<?php 
			$row = 0;
			foreach ($results as $result) {
				$row++;
				($row % 2 == 0)?$classbg='rowOdd':$classbg='rowEven';
				$i = $result->p2p_bp_service_id;
?>
                <div id="showAjax<?php echo $i; ?>" style="width:100%; background-color: #FFF;"></div>
                
                <div id="table<?php echo $i; ?>" class="serviceTable <?php echo $classbg; ?>">
                    <div class="colIdS"><?php echo $i; ?></div>
                    <div class="colActionS">
                        <div id="p2p_service_edit<?php echo $i; ?>" class="button" style="width:27px; padding: 0;"><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/images/icon_edit.png" alt="Edit this Service"/></div>
                        <div id="p2p_service_delete<?php echo $i; ?>" class="button" style="width:27px; padding: 0;"><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/images/icon_delete.png" alt="Delete this Service"/></div>
                    </div>
                    <div class="colServiceS">
                        <input type="text" id="service<?php echo $i; ?>" value="<?php echo $result->p2p_bp_service_name; ?>" class='w90p'/>
                    </div>
                </div>
                <script>                
                //Confirm Service edit    
                    jQuery("#p2p_service_edit<?php echo $i; ?>").click(function () {
                        ajaxManageService(<?php echo $i; ?>,"<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/service_edit.php","#showAjax<?php echo $i; ?>");
                    });
                
                //Confirm Service deletion
                    jQuery("#p2p_service_delete<?php echo $i; ?>").click(function () {
                        ajaxManageService(<?php echo $i; ?>,"<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/service_delete.php","#showAjax<?php echo $i; ?>");            
                    });
                </script>
<?php
			}
?>
                <div id="showAjaxAdd" style="width:100%; background-color: #FFF;"></div>    
                <div class="serviceTable serviceBottom ">
                    <div class="colIdS">#</div>
                    <div class="colActionS"><div id="p2p_service_add" class="button" style="width:27px; padding: 0;"><img src="<?php echo plugins_url(); ?>/p2p_bigpromoter/system/images/icon_add.png" alt="Add New Car"/></div></div>
                    <div class="colServiceS">
                        <input type="text" id="newService" value="" class='w90p' />
                    </div>
                </div>
            </div>
        </div>
            <script>
            //Manage Add Car	
                jQuery("#p2p_service_add").click(function () {
                    ajaxAddService("<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/service_add.php","#showAjaxAdd");
                });
            </script>
            <form method="post" action="options.php">
                <div class="p2p_bp_panel p2p_bp_panel-primary   pull-left w100p">
                    <div class="p2p_bp_panel-heading">
                            <h3 class="p2p_bp_panel-title">Service Options</h3>
                    </div>
                    <div class="p2p_bp_panel-body">
                        <div class="form-table">
                            <?php		
                                settings_fields( 'p2p_bigpromoter_service' );
                                do_settings_sections( 'p2p_bigpromoter_service' );
                                (get_option('service_others'))?$checked='checked':$checked='';
                            ?>
                            <div scope="row"><input type="checkbox" name="service_others" <?php echo $checked; ?>> Add Other on the list?</div>
                        </div>
                    </div>
                </div>
                <?php submit_button(); ?>
            </form>
