<?php
    //Include Core files from WP
    include_once ('../../include.php');

    $edit = $control->editCar();
    
    if (!$edit) {
        echo '<div id="divChange" class="alert alert-success w100p left">Info Changed!</div>';
    } else {
        echo '<div id="divChange" class="alert alert-danger w100p left">'.$edit.'</div>';
    }
?>
<script>
        jQuery("#divChange").delay(3000).fadeOut(500);
</script>