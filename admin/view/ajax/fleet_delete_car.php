<?php
    //Include Core files from WP
    include_once ('../../include.php');
    require_once( '../../../../../../wp-blog-header.php' );

    if ($_POST['del'] == 1) {
        $delete = $control->deleteCar(true);
        if ($delete) {
?>
            <div id="divDelete" class="alert alert-success w100p left">Car deleted!</div>
            <script>
                    jQuery("#divDelete").delay(2000).fadeOut(500);
            </script>
<?php            
        }
    } else {
?>
			<div class="alert alert-danger w100p left">Confirm delete car <?php echo $_POST['body']; ?>? <div id="deleteYes" class="btn btn-std bgRed">Delete</div> <div id="deleteNo" class="btn btn-std bgGray right">Close</div></div>
			<script>
				jQuery("#deleteYes").click(function () {
                    loadingDiv("#showAjax<?php echo $_POST['id']; ?>");
					jQuery.ajax({
						type: "POST",
						url: "<?php echo plugins_url(); ?>/p2p_bigpromoter/admin/view/ajax/fleet_delete_car.php",
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
        $delete = $control->deleteCar();
    }
?>