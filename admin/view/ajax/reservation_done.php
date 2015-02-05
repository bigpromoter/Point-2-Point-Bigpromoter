<?php
include_once ("../../../../../../wp-blog-header.php"); //Include WP Header
include_once ("../../include.php"); //Include classes to Admin

$update = $model->doneReservation($_POST['id'], $_POST['trip'], $_POST['value']);

if ($update) {
        echo 'Trip status changed';
}
?>