<?php include_once('../../include/include_user.php'); ?>
<?php
    if (!isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], 'p2p_bp_nonce')) wp_die('Permissions check failed!');
    $option = get_option('p2p_bp');
    
    echo $control_util->color(); //Insert Custom Color
    $distance = $control_map->gmapDistance( $_POST['add_start'], $_POST['add_end'], $option['basic']['select_distance']);
    $availableCar = $control_fleet->availableCar($distance['distance_m']);
    
    $data = array (
        "reservation_page" => $option['basic']['reservation_page'],
        "add_start" => $_POST['add_start'],
        "add_end" => $_POST['add_end'],
        "distance" => $distance,
        "availableCar" => $availableCar,
        "currency" => $option['basic']['select_currency']
    );
?>
    <div id="fleetPrice" style="width: 100%">
        <div style="text-align: center"><img src='<?php echo P2P_DIR_IMAGES;?>ajax-loader-2.gif'/></div>
    </div>
    <script>checkApi('fleetPrice', '#fleetPrice', '<?php echo serialize($data); ?>');</script>