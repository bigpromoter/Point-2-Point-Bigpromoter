<?php
    //Include Core files from WP
    include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
    include_once ('../../include.php');
    header("HTTP/1.1 200 OK");
    
    $del = ((isset($_POST['del'])) && ($_POST['del'] == 1))?true:false;
    if ($del === true) {
        $delete = $control->deleteService(true);
        if ($delete) {
?>
            <div id="divDelete" class="alert p2p_bp_alert-success w100p left">Service deleted!</div>
            <script>
                    jQuery("#divDelete").delay(2000).fadeOut(500);
            </script>
<?php            
        }
    } else {
?>
			<div class="alert p2p_bp_alert-danger w100p left">Confirm delete service <?php echo $_POST['service']; ?>? <div id="deleteYes" class="p2p_bp_btn p2p_bp_btn-std bgRed">Delete</div> <div id="deleteNo" class="p2p_bp_btn p2p_bp_btn-std bgGray right">Close</div></div>
            <script>
				jQuery("#deleteYes").click(function () {
                    loadingDiv("#showAjax<?php echo $_POST['id']; ?>");
					jQuery.ajax({
						type: "POST",
						url: "<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/service_delete.php",
						data: {
							id: <?php echo $_POST['id']; ?>,
							del: 1
						}
					}).done(function( msg ) {
						jQuery("#showAjax<?php echo $_POST['id']; ?>").html(msg);
						jQuery("#table<?php echo $_POST['id']; ?>").fadeOut();
					});		
				});
				jQuery("#deleteNo").click(function () {
					jQuery("#showAjax<?php echo $_POST['id']; ?>").html('');
				});
			</script>
<?php
        $delete = $control->deleteService();
    }
?>