<?php
    session_start();

    require_once( '../../../../../wp-blog-header.php' );
    include_once (dirname(__FILE__)."/../include.php");

    echo $control->color(); //Insert Custom Color
    
    $distance = $control->gmapDistance( $_POST['add_start'], $_POST['add_end'], get_option('select_distance'));
    $availableCar = $control->availableCar($distance['distance_m']); 
    $data = array (
                "reservation_page" => get_option('reservation_page'),
                "add_start" => $_POST['add_start'],
                "add_end" => $_POST['add_end'],
                "distance" => $distance,
                "availableCar" => $availableCar,
                "currency" => get_option('select_currency')
            );
?>
<div id="fleetPrice"><div style="position:relative; left:100%;"><img src="<?php echo plugins_url('p2p_bigpromoter/'); ?>system/images/loading.gif" width="100"/></div></div>
<script>
jQuery.ajax({
			type: "POST",
			url: 'http://www.bigpromoter.com/API/bigpromoter.php',
			data: {
                check: 'fleetPrice',
				api: '<?php echo get_option('p2p_bigpromoter_api'); ?>',
				site: '<?php echo get_option('p2p_bigpromoter_site'); ?>',
                data: '<?php echo serialize($data); ?>"'
			}
		}).done(function(msg) {
			jQuery("#fleetPrice").html(msg);
		});
</script>

    
 