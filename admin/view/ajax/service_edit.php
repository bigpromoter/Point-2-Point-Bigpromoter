<?php
    //Include Core files from WP
    include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
    include_once ('../../include.php');
    header("HTTP/1.1 200 OK");
    
    $edit = $control->editService();
    
    if (!$edit) {
        echo '<div id="divChange" class="alert p2p_bp_alert-success w100p left">Info Changed!</div>';
    } else {
        echo '<div id="divChange" class="alert p2p_bp_alert-danger w100p left">'.$edit.'</div>';
    }
?>
<script>
        jQuery("#divChange").delay(3000).fadeOut(500);
</script>